@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                        <a href="{{ route('transaction.download.pdf', $transaction->id) }}" class="btn btn-primary">
                            <i class="bi bi-download"></i> Unduh
                        </a>                    
                        <a href="{{ route('transaction.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>Invoice - #{{ $transaction->id }}</strong></p>
                    <p class="mb-0">{{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d F Y') }}</p>
                </div>
            </div>

            @if($transaction->member)
                {{-- <p class="mb-1"><strong>{{ $transaction->member->no_hp }}</strong></p> --}}
                <p class="mb-1">NO MEMBER : {{ $transaction->member->no_hp }}</p>
                <p class="mb-1">STATUS BERGABUNG : Aktif</p>
                <p class="mb-1">MEMBER SEJAK : {{ \Carbon\Carbon::parse($transaction->member->created_at)->translatedFormat('d F Y') }}</p>
                <p class="mb-3">MEMBER POIN : {{ $transaction->member->point ?? 0 }}</p>
            @endif

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
                    @foreach ($transaction->penjualan->detailPenjualan as $item)
                        <tr>
                            <td>{{ $item->produk->nama_produk }}</td>
                            <td class="text-end">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex mt-4">
                <div class="w-100 bg-light p-3">
                    <div class="row text-center">
                        <div class="col">
                            <p class="mb-1">POIN DIGUNAKAN</p>
                            <strong>{{ $transaction->poin_digunakan ?? 0 }}</strong>
                        </div>
                        <div class="col">
                            <p class="mb-1">KASIR</p>
                            <strong>{{ $transaction->user->name }}</strong>
                        </div>
                        <div class="col">
                            <p class="mb-1">KEMBALIAN</p>
                            <strong>Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</strong>
                        </div>
                        <div class="col">
                            <p class="mb-1">JUMLAH BAYAR</p>
                            <strong>Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
                <div class="bg-dark text-white text-end p-3" style="min-width: 200px;">
                    <p class="mb-1">TOTAL</p>
                    <h4 class="mb-0">Rp {{ number_format($transaction->sub_total, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
