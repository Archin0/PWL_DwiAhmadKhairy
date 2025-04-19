@extends('layouts.template')

@section('content')
<div class="row">
    <!-- Summary Cards -->
    <div class="col-lg-2 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalBarang }}</h3>
                <p><strong>Total Barang</strong></p>
            </div>
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
            <a href="{{ url('barang/') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                <p><strong>Total Pemasukan</strong></p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <a href="{{ url('transaksi/list') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                @if ($totalLaba<0)
                    <h3 style="color:red">-Rp{{ number_format(abs($totalLaba), 0, ',', '.') }}</h3>
                @else
                    <h3 style="color: green">Rp{{ number_format($totalLaba, 0, ',', '.') }}</h3>
                @endif
                
                <p><strong>Total Laba/Rugi</strong></p>
            </div>
            <div class="icon">
                <i class="fas fa-coins"></i>
            </div>
            <a href="{{ url('transaksi/list') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $pelangganHarian }}</h3>
                <p><strong>Pelanggan Harian</strong></p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ url('transaksi/list') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Weekly Income Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pemasukan 1 Minggu Terakhir</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="weeklyIncomeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Transaksi Terakhir</h3>
                <div class="card-tools">
                    <span class="badge badge-danger">{{ $recentTransactions->count() }} Transaksi</span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                    @foreach($recentTransactions as $transaction)
                    <li class="item">
                        <div class="product-info">
                            <a href="{{ url('transaksi/export_pdf', $transaction->id_transaksi) }}" target="_blank" class="product-title">
                                {{ $transaction->kode_transaksi }}
                                <span class="badge badge-success float-right">Rp{{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
                            </a>
                            <span class="product-description">
                                {{ $transaction->user->nama }} • {{ $transaction->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="{{ url('transaksi/list') }}" class="uppercase">Lihat Semua Transaksi</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Monthly Income Card -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pemasukan 1 Bulan Terakhir</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="monthlyIncomeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .small-box {
        border-radius: 0.25rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125),0 1px 3px rgba(0,0,0,.2);
        display: block;
        margin-bottom: 20px;
        position: relative;
    }
    .small-box > .inner {
        padding: 10px;
    }
    .small-box h3 {
        font-size: 2.2rem;
        font-weight: bold;
        margin: 0 0 10px 0;
        white-space: nowrap;
        padding: 0;
    }
    .small-box p {
        font-size: 1rem;
    }
    .small-box .icon {
        color: rgba(0,0,0,.15);
        z-index: 0;
        position: absolute;
        right: 15px;
        top: 15px;
        font-size: 70px;
        transition: all .3s linear;
    }
    .small-box:hover .icon {
        font-size: 75px;
    }
    .products-list .product-info {
        margin-left: 0;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Weekly Income Chart
    var weeklyCtx = document.getElementById('weeklyIncomeChart').getContext('2d');
    var weeklyChart = new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($weeklyLabels) !!},
            datasets: [{
                label: 'Pemasukan',
                data: {!! json_encode($weeklyData) !!},
                backgroundColor: 'rgba(60, 141, 188, 0.9)',
                borderColor: 'rgba(60, 141, 188, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Monthly Income Chart
    var monthlyCtx = document.getElementById('monthlyIncomeChart').getContext('2d');
    var monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{
                label: 'Pemasukan',
                data: {!! json_encode($monthlyData) !!},
                backgroundColor: 'rgba(0, 166, 90, 0.1)',
                borderColor: 'rgba(0, 166, 90, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush