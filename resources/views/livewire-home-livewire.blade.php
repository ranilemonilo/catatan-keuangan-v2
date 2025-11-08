<div class="mt-3">
    <div class="card mb-3">
        <div class="card-header d-flex">
            <div class="flex-fill">
                <h3>Halo, {{ $auth->name }}</h3>
            </div>
            <div>
                <a href="{{ route('auth.logout') }}" class="btn btn-warning">Keluar</a>
            </div>
        </div>

        <div class="card-body">
            <!-- Statistik Keuangan -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Pemasukan</h6>
                            <h4>Rp {{ number_format($totalIncome, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Pengeluaran</h6>
                            <h4>Rp {{ number_format($totalExpense, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">Saldo</h6>
                            <h4>Rp {{ number_format($balance, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total Transaksi</h6>
                            <h4>{{ $transactionCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaksi Terbaru -->
            <div class="d-flex mb-3 align-items-center">
                <div class="flex-fill">
                    <h5 class="m-0">Transaksi Terbaru</h5>
                </div>
                <div>
                    <a href="{{ route('app.statistics') }}" class="btn btn-info me-2">
                        <i class="bi bi-graph-up"></i> Statistik
                    </a>
                    <a href="{{ route('app.transactions') }}" class="btn btn-primary">
                        Lihat Semua Transaksi
                    </a>
                </div>
            </div>

            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $transaction)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($transaction->transaction_date)) }}</td>
                            <td>{{ $transaction->title }}</td>
                            <td>{{ $transaction->category }}</td>
                            <td>
                                @if($transaction->type === 'income')
                                    <span class="badge bg-success">Pemasukan</span>
                                @else
                                    <span class="badge bg-danger">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="{{ $transaction->type === 'income' ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
