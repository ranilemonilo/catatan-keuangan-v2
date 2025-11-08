<form wire:submit.prevent="editReceipt">
    <div class="modal fade" tabindex="-1" id="editReceiptModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Bukti Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Foto Baru</label>
                        <input type="file" class="form-control" wire:model="editReceiptFile" accept="image/*">
                        @error('editReceiptFile')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        @if($editReceiptFile)
                            <div class="mt-2">
                                <img src="{{ $editReceiptFile->temporaryUrl() }}" alt="Preview" style="max-width: 100%;">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" @if(!$editReceiptFile) disabled @endif>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>