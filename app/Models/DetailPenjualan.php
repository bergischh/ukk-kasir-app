<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';

    protected $fillable = [
        'id_penjualan', 'id_produk', 'quantity', 'subtotal'
    ];

    public function penjualan() {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }
    
   public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
