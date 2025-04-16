<?php


namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenjualanFilteredExport implements FromCollection, WithHeadings
{
    protected $tanggal;
    protected $bulan;
    protected $tahun;

    // ✅ Konstruktor: simpan filter dari request
    public function __construct($tanggal = null, $bulan = null, $tahun = null)
    {
        $this->tanggal = $tanggal;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    // ✅ Header kolom Excel
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

    // ✅ Ambil dan filter data sesuai tanggal/bulan/tahun
    public function collection()
    {
        $query = Penjualan::with(['transaksi.member', 'detailPenjualan.produk']);
    
        if ($this->tahun) {
            $query->whereYear('tanggal_penjualan', $this->tahun);
        }
    
        if ($this->bulan) {
            $query->whereMonth('tanggal_penjualan', $this->bulan);
        }
    
        if ($this->tanggal) {
            $query->whereDay('tanggal_penjualan', $this->tanggal);
        }
    
        return $query->get()->map(function ($penjualan) {
            $produkList = $penjualan->detailPenjualan->map(function ($detail) {
                return $detail->produk->nama_produk . ' (x' . $detail->quantity . ')';
            })->implode(', ');

            $transaksi = $penjualan->transaksi->first();
            $member = $transaksi->member ?? null;
    
            return [
                $member->nama_member ?? 'Bukan Member',
                $member->no_hp ?? '-',
                $member->point ?? '',
                $produkList,
                'Rp ' . number_format($penjualan->total_harga, 0, ',', '.'),
                'Rp ' . number_format($transaksi->total_bayar ?? 0, 0, ',', '.'),
                'Rp ' . number_format($transaksi->sub_total ?? 0, 0, ',', '.'),
                'Rp ' . number_format($transaksi->kembalian ?? 0, 0, ',', '.'),
                \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d-m-Y'),
            ];
        });
    }
}
