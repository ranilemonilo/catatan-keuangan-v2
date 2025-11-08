<div class="mt-3">
    <div class="card">
       <div class="card-header d-flex">
    <div class="flex-fill">
        <h3>Catatan Keuangan</h3>
    </div>
    <div>
        <a href="{{ route('app.statistics') }}" class="btn btn-info me-2">
            <i class="bi bi-graph-up"></i> Statistik
        </a>
        <a href="{{ route('app.home') }}" class="btn btn-secondary me-2">Kembali</a>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
            Tambah Transaksi
        </button>
    </div>
</div>
        <div class="card-body">
            <!-- Search & Filter Section -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Cari transaksi..." wire:model.live="search">
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="filterType">
                        <option value="">Semua Tipe</option>
                        <option value="income">Pemasukan</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="filterCategory">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" placeholder="Dari Tanggal" wire:model.live="filterDateFrom">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" placeholder="Sampai Tanggal" wire:model.live="filterDateTo">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-secondary w-100" wire:click="resetFilters">Reset</button>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="table-light">
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $key => $transaction)
                            <tr>
                                <td>{{ $transactions->firstItem() + $key }}</td>
                                <td>{{ date('d/m/Y', strtotime($transaction->transaction_date)) }}</td>
                                <td>{{ $transaction->title }}</td>
                                <td><span class="badge bg-secondary">{{ $transaction->category }}</span></td>
                                <td>
                                    @if($transaction->type === 'income')
                                        <span class="badge bg-success">Pemasukan</span>
                                    @else
                                        <span class="badge bg-danger">Pengeluaran</span>
                                    @endif
                                </td>
                                <td class="fw-bold {{ $transaction->type === 'income' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    <a href="{{ route('app.transactions.detail', ['transaction_id' => $transaction->id]) }}" 
                                       class="btn btn-sm btn-info">Detail</a>
                                    <button wire:click="prepareEdit({{ $transaction->id }})" 
                                            class="btn btn-sm btn-warning">Edit</button>
                                    <button wire:click="prepareDelete({{ $transaction->id }})" 
                                            class="btn btn-sm btn-danger">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('components.modals.transactions.add')
    @include('components.modals.transactions.edit')
    @include('components.modals.transactions.edit-receipt')
    @include('components.modals.transactions.delete')
</div>