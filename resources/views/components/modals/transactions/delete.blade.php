<form wire:submit.prevent="deleteTransaction">
    <div class="modal fade" tabindex="-1" id="deleteTransactionModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <strong>Peringatan!</strong> Apakah Anda yakin ingin menghapus transaksi dengan judul <strong>"{{ $deleteTitle }}"</strong>?
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ketik ulang judul untuk konfirmasi:</label>
                        <input type="text" class="form-control" wire:model="deleteConfirmTitle" placeholder="Ketik judul transaksi">
                        @error('deleteConfirmTitle')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</form>