<form wire:submit.prevent="editTransaction">
    <div class="modal fade" tabindex="-1" id="editTransactionModal" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                                <select class="form-select" wire:model="editType">
                                    <option value="">Pilih Tipe</option>
                                    <option value="income">Pemasukan</option>
                                    <option value="expense">Pengeluaran</option>
                                </select>
                                @error('editType')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" wire:model="editTransactionDate">
                                @error('editTransactionDate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="editTitle">
                        @error('editTitle')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model="editAmount" step="0.01">
                                @error('editAmount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="editCategory">
                                @error('editCategory')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" wire:ignore>
                        <label class="form-label">Deskripsi</label>
                        <input id="editDescription" type="hidden" wire:model="editDescription">
                        <trix-editor input="editDescription" placeholder="Tambahkan catatan detail..."></trix-editor>
                        @error('editDescription')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('livewire:initialized', function() {
        // Sync Trix editor with Livewire for edit modal
        Livewire.on('showModal', (data) => {
            if (data.id === 'editTransactionModal') {
                setTimeout(() => {
                    const editEditor = document.querySelector('trix-editor[input="editDescription"]');
                    if (editEditor) {
                        editEditor.editor.loadHTML(@this.get('editDescription') || '');
                        
                        editEditor.addEventListener('trix-change', function(event) {
                            @this.set('editDescription', event.target.value);
                        });
                    }
                }, 100);
            }
        });
    });
</script>