<div class="mt-3">
    @if($transaction)
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="flex-fill">
                <a href="{{ route('app.transactions') }}" class="text-decoration-none">
                    <small class="text-muted">&lt; Kembali ke Daftar Transaksi</small>
                </a>
                <h3 class="mb-0 mt-2">
                    {{ $transaction->title }}
                    @if($transaction->type === 'income')
                        <span class="badge bg-success">Pemasukan</span>
                    @else
                        <span class="badge bg-danger">Pengeluaran</span>
                    @endif
                </h3>
            </div>
            <div>
                @if($transaction->receipt)
                    <button class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#editReceiptModal">
                        Ubah Bukti
                    </button>
                    <button class="btn btn-danger" wire:click="deleteReceipt" 
                            onclick="return confirm('Yakin ingin menghapus bukti transaksi?')">
                        Hapus Bukti
                    </button>
                @else
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editReceiptModal">
                        Upload Bukti
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <!-- Informasi Transaksi -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Jumlah</h6>
                            <h2 class="mb-0 {{ $transaction->type === 'income' ? 'text-success' : 'text-danger' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Kategori</h6>
                            <h4 class="mb-0">
                                <span class="badge bg-secondary">{{ $transaction->category }}</span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <p class="mb-1 text-muted">Tanggal Transaksi</p>
                    <p class="fw-bold">{{ date('d F Y', strtotime($transaction->transaction_date)) }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1 text-muted">Dibuat pada</p>
                    <p class="fw-bold">{{ date('d F Y, H:i', strtotime($transaction->created_at)) }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1 text-muted">Terakhir diubah</p>
                    <p class="fw-bold">{{ date('d F Y, H:i', strtotime($transaction->updated_at)) }}</p>
                </div>
            </div>

            <hr>

           <!-- Deskripsi -->
<div class="mb-4">
    <h5 class="mb-3">Deskripsi</h5>
    @if($transaction->description)
        <div class="trix-content" style="font-size: 16px; line-height: 1.6;">
            {!! $transaction->description !!}
        </div>
    @else
        <p class="text-muted fst-italic">Tidak ada deskripsi</p>
    @endif
</div>

            <hr>

            <!-- Bukti Transaksi -->
            <div class="mb-3">
                <h5 class="mb-3">Bukti Transaksi</h5>
                @if($transaction->receipt)
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $transaction->receipt) }}" 
                             alt="Bukti Transaksi" 
                             class="img-fluid rounded border shadow-sm"
                             style="max-width: 100%; max-height: 600px; cursor: pointer;"
                             data-bs-toggle="modal" 
                             data-bs-target="#imagePreviewModal">
                        <p class="text-muted mt-2 small">Klik gambar untuk memperbesar</p>
                    </div>
                @else
                    <div class="alert alert-info">
                        Belum ada bukti transaksi yang diupload.
                        <button class="btn btn-sm btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#editReceiptModal">
                            Upload Sekarang
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Edit/Upload Receipt -->
    <form wire:submit.prevent="editReceipt">
        <div class="modal fade" tabindex="-1" id="editReceiptModal" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $transaction->receipt ? 'Ubah' : 'Upload' }} Bukti Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Foto</label>
                            <input type="file" class="form-control" wire:model="editReceiptFile" accept="image/*">
                            @error('editReceiptFile')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        @if($editReceiptFile)
                            <div class="mb-3">
                                <label class="form-label">Preview:</label>
                                <div class="text-center">
                                    <img src="{{ $editReceiptFile->temporaryUrl() }}" 
                                         alt="Preview" 
                                         class="img-fluid rounded border"
                                         style="max-height: 400px;">
                                </div>
                            </div>
                        @elseif($transaction->receipt)
                            <div class="mb-3">
                                <label class="form-label">Gambar Saat Ini:</label>
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $transaction->receipt) }}" 
                                         alt="Current Receipt" 
                                         class="img-fluid rounded border"
                                         style="max-height: 400px;">
                                </div>
                            </div>
                        @endif
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

    <!-- Modal Image Preview (Full Size) -->
    @if($transaction->receipt)
        <div class="modal fade" tabindex="-1" id="imagePreviewModal">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bukti Transaksi - {{ $transaction->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <img src="{{ asset('storage/' . $transaction->receipt) }}" 
                             alt="Bukti Transaksi" 
                             class="img-fluid"
                             style="max-width: 100%; height: auto;">
                    </div>
                </div>
            </div>
        </div>
    @endif
    @else
        <div class="alert alert-danger">
            Transaksi tidak ditemukan atau Anda tidak memiliki akses.
        </div>
    @endif
</div>
