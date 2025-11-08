<form wire:submit.prevent="addTransaction">
    <div class="modal fade" tabindex="-1" id="addTransactionModal" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                                <select class="form-select" wire:model="addType">
                                    <option value="">Pilih Tipe</option>
                                    <option value="income">Pemasukan</option>
                                    <option value="expense">Pengeluaran</option>
                                </select>
                                @error('addType')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" wire:model="addTransactionDate">
                                @error('addTransactionDate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="addTitle" placeholder="Contoh: Gaji Bulan Januari">
                        @error('addTitle')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model="addAmount" placeholder="0" step="0.01">
                                @error('addAmount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="addCategory" placeholder="Contoh: Makanan, Transport, Gaji">
                                @error('addCategory')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" wire:ignore>
                        <label class="form-label">Deskripsi</label>
                        <input id="addDescription" type="hidden" wire:model="addDescription">
                        <trix-editor input="addDescription" placeholder="Tambahkan catatan detail..."></trix-editor>
                        @error('addDescription')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bukti Transaksi (Foto)</label>
                        <input type="file" class="form-control" wire:model="addReceipt" accept="image/*">
                        @error('addReceipt')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        @if($addReceipt)
                            <div class="mt-2">
                                <img src="{{ $addReceipt->temporaryUrl() }}" alt="Preview" style="max-width: 200px;">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sync Trix editor with Livewire
        const addEditor = document.querySelector('#addDescription');
        if (addEditor) {
            addEditor.addEventListener('trix-change', function(event) {
                @this.set('addDescription', event.target.value);
            });
        }
        
        // Clear Trix editor when modal closes
        Livewire.on('clearTrixEditor', (data) => {
            const editor = document.querySelector('trix-editor[input="' + data.id + '"]');
            if (editor) {
                editor.editor.loadHTML('');
            }
        });
    });
</script>