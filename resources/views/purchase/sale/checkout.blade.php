@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h3>Checkout Page</h3>
    <div class="card mt-3 p-5">
        <div class="row">
            <!-- Produk yang dipilih -->
            <div class="col-md-6">
                <h4>Produk yang Dipilih</h4>
                @php
                    $totalHarga = 0;
                @endphp
                @if (session('cart'))
                    @php
                        $totalHarga = 0;
                    @endphp
                    <ul class="list-group">
                        @foreach ($produks as $produk)
                            @php
                                $qty = session('cart')[$produk->id] ?? 0;
                                if ($qty > 0) {
                                    $subtotal = $produk->harga * $qty;
                                    $totalHarga += $subtotal;
                            @endphp
                            <li class="mt-3 d-flex justify-content-between align-items-center">
                                {{ $produk->nama_produk }} ({{ $qty }}x)
                                <span class="">Rp. {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </li>
                            @php
                                }
                            @endphp
                        @endforeach
                    </ul>
                @else
                    <p>Tidak ada produk yang dipilih.</p>
                @endif


                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <h4><b>Total Harga</b></h4>
                    <p style="font-size: 20px;"><b>Rp. {{ number_format($totalHarga, 0, ',', '.') }}</b></p>
                </div>
            </div>

            <!-- Form Checkout -->
            <div class="col-md-6">
                <form id="checkoutForm" action="{{ route('transaction.checkout.store') }}" method="POST" class="g-3" onsubmit="return handleSubmit(event)">
                    @csrf
                    @foreach ($produks as $produk)
                        <input type="hidden" name="produk[{{ $produk->id }}]" value="{{ session('cart')[$produk->id] ?? 0 }}">
                    @endforeach

                    <div class="mb-3">
                        <label for="ember" class="form-label">Member Status <span class="text-danger" style="font-size: 14px;">Dapat juga membuat member</span></label>
                        <select name="member" id="member" class="form-control my-1">
                            <option selected>Pilih Status Member</option>
                            <option value="member">Member</option>
                            <option value="non-member">Non-member</option>
                        </select>
                    </div>

                    <div class="mb-3" id="phone-field" style="display: none;">
                        <label for="no_hp" class="form-label">No Telepon <span class="text-danger">(Wajib untuk Member)</span></label>
                        <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="Masukkan No Telepon">
                    </div>

                    <input type="hidden" name="total_harga" value="{{ $totalHarga }}">
                    

                    <div class="mb-3">
                        <label for="total_bayar" class="form-label">Total Bayar</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" name="total_bayar" id="total_bayar" 
                            oninput="formatRupiah(this)" required>
                        </div>
                        <span id="error-message" class="text-danger" style="display: none; font-size: 14px;">
                            Total bayar tidak boleh kurang dari total harga!
                        </span>
                    </div>
                    @if ($totalHarga == 0)
                        <p class="text-danger">Produk belum dipilih. Silakan pilih produk terlebih dahulu.</p>
                    @else
                        <button type="submit" class="btn btn-primary py-1 px-3" style="border: none; border-radius: 7px;">Pesan</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("member").addEventListener("change", function() {
        let phoneField = document.getElementById("phone-field");
        phoneField.style.display = this.value === "member" ? "block" : "none";
    });

    function formatRupiah(el) {
        let angka = el.value.replace(/\D/g, ""); 
        el.value = angka.replace(/\B(?=(\d{3})+(?!\d))/g, "."); 
    }

    function checkTotalBayar() {
        let totalHarga = {{ $totalHarga }};
        let totalBayarInput = document.getElementById("total_bayar").value.replace(/\./g, ""); 
        let totalBayar = parseInt(totalBayarInput) || 0;
        let errorMessage = document.getElementById("error-message");

        if (totalBayar < totalHarga) {
            errorMessage.style.display = "block";
            return false;
        } else {
            errorMessage.style.display = "none";
            return true;
        }
    }

    // hapus titik
    function removeDotBeforeSubmit() {
        let totalBayarInput = document.getElementById("total_bayar");
        totalBayarInput.value = totalBayarInput.value.replace(/\./g, ""); 
    }

    function handleSubmit(event) {
        event.preventDefault(); 

        let form = document.getElementById("checkoutForm");
        let memberStatus = document.getElementById("member").value;
        removeDotBeforeSubmit(); 

        if (!checkTotalBayar()) {
            return false; 
        }

        if (memberStatus === "member") {
            let formData = new FormData(form);

            fetch("{{ route('transaction.member.storeSession') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                }
            }).then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    window.location.href = "{{ route('transaction.member.checkout') }}"; // Pindah ke halaman co_member
                }
            }).catch(error => console.error("Error:", error));

            return false; 
        } else {
            form.method = "POST";
            form.action = "{{ route('transaction.checkout.store') }}";
            form.submit();
        }
    }

    document.getElementById("total_bayar").addEventListener("input", function() {
        formatRupiah(this);
        checkTotalBayar();
    });
</script>
@endsection
