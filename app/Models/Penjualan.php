<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = [
        'total_harga', 'tanggal_penjualan', 'total_produk', 'id_produk'
    ];

    public function produk() {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function transaksi() {
        return $this->hasMany(Transaksi::class, 'id_penjualan');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
    }
}
