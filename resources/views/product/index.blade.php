@extends('layouts.template')

@section('content')
<div class="container-fluid">
    {{-- Heading --}}
    <h1 class="h3 mb-3 text-gray-800">Product Data Table</h1>

    @if (auth()->user() && auth()->user()->role === 'admin')
    <a href="{{ route('product.create') }}" class="btn btn-info mb-3">
        <i class="fas fa-plus-circle me-1"></i> Tambah Data
    </a>  
    @endif

    {{-- Tables --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Produk</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="product" class="table table-striped text-center align-middle" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            @if (auth()->user() && auth()->user()->role === 'admin')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no= 1;
                        @endphp
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $product->gambar_produk) }}" alt="Gambar Produk" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                </td>
                                <td>{{ $product['nama_produk'] }}</td>
                                <td>Rp {{ number_format($product['harga'], 0, ',', '.') }}</td>
                                <td>{{ $product['stock'] }}</td>
                                @if (auth()->user() && auth()->user()->role === 'admin')
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('product.edit', $product['id']) }}" class="btn btn-warning btn-sm mx-1 ">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-info btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#modalStock{{ $product->id }}">
                                            <i class="fas fa-box"></i> Stock
                                        </button>
                                        <form action="{{ route('product.delete', $product['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mx-1">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endforeach                           
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Stock -->
@foreach ($products as $product)
    <div class="modal fade" id="modalStock{{ $product->id }}" tabindex="-1" aria-labelledby="modalStockLabel{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalStockLabel{{ $product->id }}">Update Stock - {{ $product->nama_produk }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('product.updateStock', $product->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" value="{{ $product->nama_produk }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
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
            $('#product').DataTable();
        });
    </script>
@endsection
