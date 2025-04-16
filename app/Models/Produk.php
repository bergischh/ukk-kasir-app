<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    
    protected $fillable = [
        'nama_produk', 'harga', 'stock', 'gambar_produk'
    ];

    protected $casts = [
        'harga' => 'integer',
    ];

    
    public function penjualan() {
        return $this->hasMany(Penjualan::class, 'id_produk');
    }

    public function detail_penjualan() {
        return $this->hasMany(DetailPenjualan::class);
    }
}
