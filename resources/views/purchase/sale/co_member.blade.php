@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h3>Checkout Member</h3>
    <div class="card mt-3 p-5">
        <div class="row">
            <!-- Produk yang dipilih -->
            <div class="col-md-6">
                <h4>Produk yang Dipilih</h4>
                <ul class="list-group">
                    @php 
                        $totalHarga = 0; 
                    @endphp
                    @foreach ($checkoutData['produk'] as $id => $qty)
                        @php
                            $produk = \App\Models\Produk::find($id);
                            $subtotal = $produk->harga * $qty;
                            $totalHarga += $subtotal;
                            $totalBayar = $checkoutData['total_bayar'] ?? 0;
                        @endphp
                        <li class="mt-3 d-flex justify-content-between align-items-center">
                            {{ $produk->nama_produk }} ({{ $qty }}x)
                            <span class="">Rp. {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <h4><b>Total Harga</b></h4>
                    <p style="font-size: 20px;"><b>Rp. {{ number_format($totalHarga, 0, ',', '.') }}</b></p>
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <h4><b>Total Bayar</b></h4>
                    <p style="font-size: 20px;"><b>Rp. {{ number_format($totalBayar, 0, ',', '.') }}</b></p>
                </div>
            </div>

            <!-- Form Pendaftaran Member -->
            <div class="col-md-6">
                <form action="{{ route('transaction.member.process') }}" method="POST">
                    @csrf
                    @php
                        $existingMember = \App\Models\Member::where('no_hp', $checkoutData['no_hp'])->first();
                        $estimatedPoint = round($totalHarga * 0.10);
                    @endphp

                    <div class="mb-3">
                        <label for="nama_member" class="form-label">Nama Member</label>
                        <input type="text" class="form-control"
                            name="nama_member"
                            id="nama_member"
                            value="{{ $existingMember->nama_member ?? '' }}"
                            {{ $existingMember ? 'readonly' : '' }}
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Poin</label>
                        <input type="text"
                            class="form-control"
                            value="{{ $existingMember ? $existingMember->point : $estimatedPoint }}"
                            readonly>
                    </div>

                    <!-- Hidden poin_digunakan -->
                    <input type="hidden" name="poin_digunakan" id="poin_digunakan" value="0">

                    <input type="hidden" name="point" value="{{ $existingMember->point ?? 0 }}">

                    <div class="form-check mb-3">
                        <input class="form-check-input"
                            type="checkbox"
                            value="1"
                            id="pakai_point"
                            name="pakai_point"
                            {{ !$existingMember || $existingMember->point == 0 ? 'disabled' : '' }}>
                        <label class="form-check-label" for="pakai_point">
                            Gunakan point untuk potongan harga
                        </label>
                    </div>

                    <input type="hidden" name="no_hp" value="{{ $checkoutData['no_hp'] }}">

                    <button type="submit" class="btn btn-primary py-1 px-3">
                        {{ $existingMember ? 'Checkout Sekarang' : 'Daftar & Checkout' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk update poin_digunakan -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('pakai_point');
        const poinInput = document.getElementById('poin_digunakan');
        const poinTersedia = {{ $existingMember->point ?? 0 }};

        checkbox.addEventListener('change', function () {
            poinInput.value = this.checked ? poinTersedia : 0;
        });
    });
</script>
@endsection
