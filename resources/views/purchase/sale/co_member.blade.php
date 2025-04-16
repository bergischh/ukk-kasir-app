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
                </ul>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <h4><b>Total Harga</b></h4>
                    <p style="font-size: 20px;"><b>Rp. 20.00,00</b></p>
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <h4><b>Total Bayar</b></h4>
                    <p style="font-size: 20px;"><b>Rp. 20.000,00</b></p>
                </div>
            </div>

            <!-- Form Pendaftaran Member -->
            <div class="col-md-6">
                <form action="" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nama_member" class="form-label">Nama Member</label>
                        <input type="text" class="form-control"
                            name="nama_member"
                            id="nama_member"
                            value="Member"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Poin
                        </label>
                        <input type="text"
                            class="form-control"
                            value="900"
                            readonly>
                    </div>

                    <input type="hidden" name="point" value="poin_hidden">

                    <div class="form-check mb-3">
                        <input class="form-check-input"
                            type="checkbox"
                            value="1"
                            id="pakai_point"
                            name="pakai_point"
                            >
                        <label class="form-check-label" for="pakai_point">
                            Gunakan point untuk potongan harga
                        </label>
                    </div>

                    <input type="hidden" name="no_hp" value="0839393993">

                    <button type="submit" class="btn btn-primary py-1 px-3">
                        Checkout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
