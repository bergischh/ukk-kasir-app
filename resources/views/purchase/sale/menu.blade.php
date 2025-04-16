@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4 text-center">
        <div class="row p-4">
            @foreach ($produks as $produk)   
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow">
                    <img src="{{ $produk->gambar_produk ? asset('storage/' . $produk->gambar_produk) : asset('default-image.jpg') }}" 
                         class="card-img-top img-fluid mx-auto" 
                         alt="{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}" 
                         style="height: 150px; width: 150px; padding: 10px; align-items: center;">

                    <div class="card-body">
                        <h5 class="card-title" style="color: black">
                            {{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}
                        </h5>
                        <p>Stok : {{ $produk->stock }}</p>
                        <p style="color: black">Harga : Rp. 
                            <span id="harga-{{ $produk->id }}">{{ number_format($produk['harga'], 0, ',', '.') }}</span>
                        </p>
                        
                        <div class="quantity d-flex justify-content-center align-items-center">
                            <button class="btn btn-danger btn-sm me-2 mx-2" onclick="updateQuantity({{ $produk->id }}, -1)">-</button>
                            <input 
                                type="text" 
                                id="quantity-{{ $produk->id }}" 
                                name="produk[{{ $produk->id }}]" 
                                value="0" 
                                class="form-control text-center" 
                                style="width: 60px;" 
                                readonly 
                                data-stock="{{ $produk->stock }}">
                            <button class="btn btn-success btn-sm ms-2 mx-2" onclick="updateQuantity({{ $produk->id }}, 1)">+</button>
                        </div>

                        <p class="mt-2">Sub Total : <span id="subtotal-{{ $produk->id }}">Rp. 0</span></p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>  

    <form id="checkoutForm" action="{{ route('transaction.cart') }}" method="POST">
        @csrf
        @foreach ($produks as $produk)
            <input type="hidden" name="produk[{{ $produk->id }}]" id="input-{{ $produk->id }}" value="0">
        @endforeach

        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-success px-5 py-2">
                Selanjutnya
            </button>
        </div>
    </form>
</div>

<script>
let cart = JSON.parse(localStorage.getItem('cart')) || {};

document.addEventListener('DOMContentLoaded', function() {
    Object.keys(cart).forEach(productId => {
        let quantityInput = document.getElementById('quantity-' + productId);
        let hiddenInput = document.getElementById('input-' + productId);
        let subtotalSpan = document.getElementById('subtotal-' + productId);
        let harga = parseInt(document.getElementById('harga-' + productId).innerText.replace(/\D/g, "")) || 0;

        if (quantityInput && hiddenInput) {
            quantityInput.value = cart[productId];
            hiddenInput.value = cart[productId];

            // Update subtotal saat halaman dimuat
            let subtotal = cart[productId] * harga;
            subtotalSpan.innerText = "Rp. " + subtotal.toLocaleString('id-ID');
        }
    });
});

function updateQuantity(productId, change) {
    let quantityInput = document.getElementById('quantity-' + productId);
    let hiddenInput = document.getElementById('input-' + productId);
    let subtotalSpan = document.getElementById('subtotal-' + productId);
    let harga = parseInt(document.getElementById('harga-' + productId).innerText.replace(/\D/g, "")) || 0;

    let currentQuantity = parseInt(quantityInput.value) || 0;
    let stock = parseInt(quantityInput.dataset.stock) || 0;

    let newQuantity = currentQuantity + change;

    if (newQuantity < 0) newQuantity = 0;

    if (newQuantity > stock) {
        alert("Stok produk tidak mencukupi! Maksimal hanya " + stock + " item.");
        return;
    }

    quantityInput.value = newQuantity;
    hiddenInput.value = newQuantity;

    // Hitung subtotal baru
    let subtotal = newQuantity * harga;
    subtotalSpan.innerText = "Rp. " + subtotal.toLocaleString('id-ID');

    // Simpan perubahan di localStorage
    cart[productId] = newQuantity;
    localStorage.setItem('cart', JSON.stringify(cart));
}

document.getElementById('checkoutForm').addEventListener('submit', function(event) {
    let valid = false;

    // Hapus semua input produk kosong sebelum submit
    Object.keys(cart).forEach(productId => {
        let hiddenInput = document.getElementById('input-' + productId);
        if (hiddenInput) {
            if (cart[productId] > 0) {
                hiddenInput.value = cart[productId]; // Simpan hanya produk yang dipilih
                valid = true;
            } else {
                hiddenInput.remove(); // Hapus input yang kosong agar tidak dikirim
            }
        }
    });

    if (!valid) {
        event.preventDefault(); // Batalkan submit jika tidak ada produk yang dipilih
        alert("Harap pilih minimal 1 produk sebelum checkout!");
    } else {
        localStorage.removeItem('cart'); // Hapus cart dari localStorage setelah submit
    }
});
</script>

@endsection
