<div class="mt-3">
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
            <div class="flex-fill">
                <h3 class="mb-0">Statistik Keuangan</h3>
            </div>
            <div class="d-flex gap-2">
                <select class="form-select" wire:model.live="selectedYear" style="width: auto;">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <a href="{{ route('app.home') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Pemasukan</h6>
                    <h3 class="mb-0">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                    <small>Tahun {{ $selectedYear }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Pengeluaran</h6>
                    <h3 class="mb-0">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                    <small>Tahun {{ $selectedYear }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Saldo</h6>
                    <h3 class="mb-0">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
                    <small>Selisih tahun {{ $selectedYear }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Jumlah Transaksi</h6>
                    <h3 class="mb-0">{{ $transactionCount }}</h3>
                    <small>Tahun {{ $selectedYear }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row mb-4">
        <!-- Monthly Trend Chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tren Pemasukan & Pengeluaran Bulanan</h5>
                </div>
                <div class="card-body">
                    <div id="monthlyChart"></div>
                </div>
            </div>
        </div>

        <!-- Type Comparison Chart -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Perbandingan Total</h5>
                </div>
                <div class="card-body">
                    <div id="typeComparisonChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row mb-4">
        <!-- Category Breakdown Chart -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pengeluaran Berdasarkan Kategori (Top 10)</h5>
                </div>
                <div class="card-body">
                    <div id="categoryChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        renderCharts();
        
        // Re-render charts when year changes
        Livewire.on('chartDataUpdated', () => {
            renderCharts();
        });
        
        // Re-render on Livewire updates
        Livewire.hook('morph.updated', () => {
            setTimeout(() => renderCharts(), 100);
        });
    });

    function renderCharts() {
        // Monthly Trend Chart (Line Chart)
        const monthlyOptions = {
            series: [{
                name: 'Pemasukan',
                data: @json($monthlyData['income'])
            }, {
                name: 'Pengeluaran',
                data: @json($monthlyData['expense'])
            }],
            chart: {
                height: 350,
                type: 'line',
                toolbar: {
                    show: true
                },
                zoom: {
                    enabled: true
                }
            },
            colors: ['#28a745', '#dc3545'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            },
            legend: {
                position: 'top'
            }
        };

        const monthlyChart = document.querySelector("#monthlyChart");
        if (monthlyChart) {
            monthlyChart.innerHTML = '';
            new ApexCharts(monthlyChart, monthlyOptions).render();
        }

        // Type Comparison Chart (Donut Chart)
        const typeComparisonOptions = {
            series: [@json($typeComparisonData['income']), @json($typeComparisonData['expense'])],
            chart: {
                type: 'donut',
                height: 350
            },
            labels: ['Pemasukan', 'Pengeluaran'],
            colors: ['#28a745', '#dc3545'],
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function(w) {
                                    const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return 'Rp ' + total.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        };

        const typeComparisonChart = document.querySelector("#typeComparisonChart");
        if (typeComparisonChart) {
            typeComparisonChart.innerHTML = '';
            new ApexCharts(typeComparisonChart, typeComparisonOptions).render();
        }

        // Category Chart (Bar Chart)
        const categoryOptions = {
            series: [{
                name: 'Pengeluaran',
                data: @json($categoryData['amounts'])
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: true
                }
            },
            colors: ['#007bff'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                    distributed: false,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return 'Rp ' + val.toLocaleString('id-ID');
                },
                offsetX: 0,
                style: {
                    fontSize: '12px',
                    colors: ['#304758']
                }
            },
            xaxis: {
                categories: @json($categoryData['categories']),
                labels: {
                    formatter: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        };

        const categoryChart = document.querySelector("#categoryChart");
        if (categoryChart) {
            categoryChart.innerHTML = '';
            new ApexCharts(categoryChart, categoryOptions).render();
        }
    }
</script>
@endpush
