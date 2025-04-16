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
</style>

<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Penjualan Data Table</h1>
    
    <div class="d-flex mb-3 justify-content-between">
      @if (auth()->user() && auth()->user()->role === 'employee')
        <a href="{{ route('purchase.menu') }}" class="btn btn-info">
            <i class="fas fa-plus-circle me-1"></i> Tambah Penjualan
        </a>
      @endif
        {{-- <div class="card p-4 shadow-sm mb-4">
            <h5 class="mb-3">Filter Penjualan untuk Export</h5>
            <form action="{{ route('penjualan.export.data') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label">Tanggal Pembelian</label>
                        <select name="tanggal" class="form-control">
                            <option value="">-- Pilih Tanggal --</option>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select" class="form-control">
                            <option value="">-- Pilih Bulan --</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control" placeholder="Contoh: 2025">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-excel me-1"></i> Export Berdasarkan Filter
                    </button>
                </div>
            </form>
        </div> --}}


        {{-- <a href="{{ route('penjualan.export') }}" class="btn btn-success">
            <i class="fas fa-file-excel me-1"></i> Export Penjualan (.xlsx)
        </a> --}}
    </div>



    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Produk</h6>
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
</div>

{{-- Modal untuk setiap transaksi --}}
@foreach ($transactions as $data)
<div class="modal fade" id="modalBukti{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content p-3">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Detail Penjualan</h5>
        <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="mb-2 d-flex justify-content-between small fs-6">
            <span><strong>Member Status:</strong> {{ $data->member ? 'Member' : 'Bukan Member' }}</span>
            <span><strong>Bergabung Sejak:</strong> 
                {{ $data->member?->tanggal_daftar ? \Carbon\Carbon::parse($data->member->tanggal_daftar)->format('d F Y') : '-' }}
            </span>
        </div>
        <div class="mb-2 small fs-6">
            <div>No. HP: {{ $data->member->no_hp ?? '-' }}</div>
            <div>Poin Member: {{ $data->member->point ?? 0 }}</div>
        </div>

        <table class="table table-sm mt-3">
          <thead>
            <tr>
              <th>Nama Produk</th>
              <th class="text-center">Qty</th>
              <th class="text-end">Harga</th>
              <th class="text-end">Sub Total</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($data->penjualan?->detailPenjualan ?? [] as $item)
            <tr>
              <td>{{ $item->produk->nama_produk ?? '-' }}</td>
              <td class="text-center">{{ $item->quantity ?? 0 }}</td>
              <td class="text-end">Rp. {{ number_format($item->produk->harga ?? 0, 0, ',', '.') }}</td>
              <td class="text-end">Rp. {{ number_format(($item->quantity ?? 0) * ($item->produk->harga ?? 0), 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="text-center">Tidak ada produk</td>
            </tr>
            @endforelse
          </tbody>
        </table>

        <div class="d-flex justify-content-between mt-3">
          <strong>Total:</strong>
          <strong>Rp. {{ number_format($data->penjualan->total_harga ?? 0, 0, ',', '.') }}</strong>
        </div>
        <div class="mt-3 text-muted small fs-6">
          <div>Dibuat pada : {{ $data->created_at?->format('Y-m-d H:i:s') }}</div>
          <div>Oleh : {{ $data->user->name ?? '-' }}</div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>


@endforeach
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css"></script>

    <!-- Inisialisasi DataTables -->
    <script>
        $(document).ready(function() {
            $('#sale').DataTable();
        });
    </script>
@endsection
  
