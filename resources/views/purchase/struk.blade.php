@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                        <a href="" class="btn btn-primary">
                            <i class="bi bi-download"></i> Unduh
                        </a>                    
                        <a href="" class="btn btn-secondary">Kembali</a>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>Invoice - #21</strong></p>
                    <p class="mb-0">20 November 2020</p>
                </div>
            </div>

                <p class="mb-1"><strong>0898828384828</strong></p>
                <p class="mb-1">NO MEMBER : 08399383838</p>
                <p class="mb-1">STATUS BERGABUNG : Aktif</p>
                <p class="mb-1">MEMBER SEJAK : 12 Oktober 0303</p>
                <p class="mb-3">MEMBER POIN : 3000</p>

            <table class="table table-borderless">
                <thead>
                    <tr class="border-bottom">
                        <th>Produk</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>Brownies</td>
                            <td class="text-end">Rp. 30.000,00</td>
                            <td class="text-center">2</td>
                            <td class="text-end">Rp. 60.000,00</td>
                        </tr>
                </tbody>
            </table>

            <div class="d-flex mt-4">
                <div class="w-100 bg-light p-3">
                    <div class="row text-center">
                        <div class="col">
                            <p class="mb-1">POIN DIGUNAKAN</p>
                            <strong>900</strong>
                        </div>
                        <div class="col">
                            <p class="mb-1">KASIR</p>
                            <strong>Petugas 1</strong>
                        </div>
                        <div class="col">
                            <p class="mb-1">KEMBALIAN</p>
                            <strong>0</strong>
                        </div>
                    </div>
                </div>
                <div class="bg-dark text-white text-end p-3" style="min-width: 200px;">
                    <p class="mb-1">TOTAL</p>
                    <h4 class="mb-0">Rp. 60.000,00</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
