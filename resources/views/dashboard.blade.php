@extends('layouts.template')

@section('content')

<style>
    .card-header {
    font-weight: 600;
    font-size: 1.1rem;
}

.display-4 {
    font-size: 2.5rem;
}

</style>

<div class="container-fluid px-4">
    
    @if ($message = Session::get('success')) 
        <script>
            Swal.fire({
                title: "Success",
                icon: "success",
                text: "{{ $message }}",
                draggable: true
            });
        </script>
    @endif

    @if ($message = Session::get('failed')) 
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ $message }}"
            });
        </script>
    @endif

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="card">
        <div class="card-body">
            @if (Auth::user()->role === 'employee')
                  <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h3 class="fw-bold text-dark mb-4">Selamat Datang Petugas</h3>

                            <div class="card shadow-sm border-0 mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0 text-primary">Total Penjualan Hari Ini</h5>
                                </div>
                                <div class="card-body text-center">
                                    <h1 class="display-4 fw-bold text-dark">{{ $salesCountToday ?? 0 }}</h1>
                                    <p class="text-muted mb-0">Jumlah total penjualan yang terjadi hari ini.</p>
                                </div>
                                <div class="card-footer bg-light text-center">
                                    <small class="text-muted">
                                        {{ $latestTransaksi ? \Carbon\Carbon::parse($latestTransaksi->created_at)->format('d M Y H:i') : 'Belum ada transaksi' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                  </div>
            @elseif (Auth::user()->role === 'admin')
            <h3 class="fw-bold text-dark mb-4">Selamat Datang Petugas</h3>
                <div class="row">
                    <div class="col-md-8">
                        <canvas id="salesChart"></canvas>
                    </div>
                    <div class="col-md-4">
                        <p class="text-secondary fw-bold text-center">Persentase Penjualan Produk</p>
                        <canvas id="productChart" style="height: 300px; width: 100%;"></canvas>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dates = {!! json_encode($dates ?? []) !!};
            var salesCount = {!! json_encode($salesCountChart ?? []) !!};
            var productNames = {!! json_encode($productNames ?? []) !!};
            var productTotals = {!! json_encode($productTotals ?? []) !!};

        
            if (document.getElementById('salesChart')) {
                const ctxBar = document.getElementById('salesChart').getContext('2d');
                const salesChart = new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: dates,
                        datasets: [{
                            label: 'Jumlah Penjualan',
                            data: salesCount,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }
        
            if (document.getElementById('productChart')) {
                const ctxPie = document.getElementById('productChart').getContext('2d');
                const productChart = new Chart(ctxPie, {
                    type: 'pie',
                    data: {
                        labels: productNames,
                        datasets: [{
                            data: productTotals,
                            backgroundColor: [
                                '#ff6384', '#36a2eb', '#ffce56',
                                '#4bc0c0', '#9966ff', '#ffa500'
                            ]
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }
        });
        </script>
        
</div>

@endsection