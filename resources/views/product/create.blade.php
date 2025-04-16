@extends('layouts.template')

@section('content')
    <div class="col-lg-12">
        <div class="card mb-2 p-5">
            <form class="row g-3" method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data" onsubmit="return cleanPriceBeforeSubmit()">
                @csrf
                @if (Session::get('success'))
                    <div class="alert alert-success"> {{ Session::get('success') }}</div>
                @endif
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="col-md-6">
                    <label for="nama_produk" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk">
                </div>

                <div class="col-md-6">
                    <label for="gambar_produk" class="form-label">Product Image</label><br>
                    <input type="file" class="d-none" id="gambar_produk" name="gambar_produk">
                    <label for="gambar_produk" class="btn btn-outline-primary px-4 w-70">
                        <i class="fas fa-upload"></i> Choose Image
                    </label>
                </div>

                <div class="col-md-6 mt-5">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock">
                </div>

                <div class="col-md-6 mt-5">
                    <label for="harga" class="form-label">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control" id="harga" name="harga" oninput="formatRupiah(this)">
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fas fa-save"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
function formatRupiah(input) {
    let angka = input.value.replace(/[^0-9]/g, ''); // Hanya angka
    let formatted = angka.replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Tambahkan titik setiap 3 digit
    input.value = formatted;
}

function cleanPriceBeforeSubmit() {
    let hargaInput = document.getElementById("harga");
    hargaInput.value = hargaInput.value.replace(/\./g, ""); // Hapus titik sebelum submit
    return true; // Lanjutkan submit
}
</script>
@endsection
