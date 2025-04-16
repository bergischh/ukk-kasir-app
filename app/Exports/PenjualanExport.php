<?php


namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenjualanExport implements FromCollection, WithHeadings
{
    // Heading untuk Excel
    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'No HP Pelanggan',
            'Poin Pelanggan',
            'Produk',
            'Total Harga',
            'Total Bayar',
            'Total Diskon',
            'Total Kembalian',
            'Tanggal Pembelian',
        ];
    }

    // Mengambil data dan mengubah menjadi format Excel
   public function collection()
    {
        return Penjualan::with(['transaksi.member', 'detailPenjualan.produk']) // Relasi yang sesuai
            ->get()
            ->map(function ($penjualan) {

                $produkList = $penjualan->detailPenjualan->map(function ($detail) {
                    return $detail->produk->nama_produk . ' (x' . $detail->quantity . ')';
                })->implode(', ');

                $transaksi = $penjualan->transaksi->first();

                $member = optional($transaksi)->member;

                return [
                    'Nama Pelanggan'     => $member->nama_member ?? 'Bukan Member',
                    'No HP Pelanggan'    => $member->no_hp ?? '-',
                    'Poin Pelanggan'     => $member->point ?? '-',
                    'Produk'             => $produkList,
                    'Total Harga'        => 'Rp ' . number_format($penjualan->total_harga, 0, ',', '.'),
                    'Total Bayar'        => 'Rp ' . number_format(optional($transaksi)->total_bayar ?? 0, 0, ',', '.'),
                    'Total Diskon'       => optional($transaksi)->sub_total > 0 
                                                ? 'Rp ' . number_format(optional($transaksi)->sub_total, 0, ',', '.') 
                                                : 'Rp 0',
                    'Total Kembalian'    => 'Rp ' . number_format(optional($transaksi)->kembalian ?? 0, 0, ',', '.'),
                    'Tanggal Pembelian'  => \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d-m-Y'),
                ];
            });
    }

}
