@extends('layouts.template')

@section('content')
<style>
    .modal-content {
        border-radius: 6px;
        font-family: Arial, sans-serif;
    }

    .modal-body table th,
    .modal-body table td {
        font-size: 15px;
    }

    .modal-title {
        font-size: 20px;
    }

    .modal-header .btn {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #000;
    }

    .btn-group {
        display: flex;
        justify-content: space-between;
    }

    .btn-group .btn {
        margin-right: 10px;
    }

    /* Untuk penataan form export */
    .card {
        padding: 20px;
    }
</style>

<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Penjualan Data Table</h1>

    <div class="d-flex mb-3 justify-content-between">
        @if (auth()->user() && auth()->user()->role === 'employee')
        <a href="{{ route('purchase.menu') }}" class="btn btn-info">
            <i class="fas fa-plus-circle me-1"></i> Tambah Penjualan
        </a>
        @endif
    </div>

    <div class="card p-4 shadow-sm mb-4">
        <h5 class="mb-3">Filter Penjualan</h5>
        <!-- Filter Form with automatic submit -->
        <form action="{{ route('purchase.index') }}" method="GET" id="filterForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="tanggal" class="form-label">Tanggal Pembelian</label>
                    <select name="tanggal" class="form-control" onchange="submitForm()">
                        <option value="">-- Pilih Tanggal --</option>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}" {{ request()->tanggal == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="bulan" class="form-label">Bulan</label>
                    <select name="bulan" id="bulan" class="form-select" onchange="submitForm()">
                        <option value="">-- Pilih Bulan --</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request()->bulan == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromFormat('m', $i)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="tahun" class="form-label">Tahun</label>
                    <input type="number" name="tahun" id="tahun" class="form-control" value="{{ request()->tahun }}" onchange="submitForm()">
                </div>
            </div>
        </form>
    </div>

    <!-- Table Produk -->
    @if($transactions->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Penjualan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="sale" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Member</th>
                            <th>Tanggal Penjualan</th>
                            <th>Total Harga</th>
                            <th>Dibuat Oleh</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $transaction->member->nama_member ?? 'NON-MEMBER' }}</td>
                            <td>{{ $transaction->penjualan?->tanggal_penjualan ?? '-' }}</td>
                            <td>Rp. {{ number_format($transaction->penjualan?->total_harga ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $transaction->user->name ?? '-' }}</td>
                            <td class="d-flex mx-auto justify-content-center">
                                <button type="button" class="btn btn-warning btn-icon-split me-1 px-2" data-bs-toggle="modal" data-bs-target="#modalBukti{{ $transaction->id }}">
                                    <span class="text">Lihat</span>
                                </button>
                                <a href="{{ route('transaction.download.pdf', $transaction->id) }}" class="btn btn-primary btn-icon-split me-1 ml-4">
                                    <span class="text">Unduh Bukti</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        Tidak ada data yang sesuai dengan filter yang dipilih.
    </div>
    @endif

    <!-- Export Button (will export the filtered data) -->
    <div class="mt-4">
        <a href="{{ route('purchase.exportFiltered', request()->all()) }}" class="btn btn-success">
            <i class="fas fa-file-excel me-1"></i> Export Berdasarkan Filter
        </a>
    </div>

</div>

<script>
    // Function to submit the filter form automatically when filter values change
    function submitForm() {
        document.getElementById('filterForm').submit();
    }
</script>

@endsection
