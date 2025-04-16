<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'kembalian', 'total_bayar', 'sub_total', 'id_member', 'id_users', 'id_penjualan'
    ];

    public function member() {
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function penjualan() {
        return $this->belongsTo(Member::class, 'id_penjualan');
    }
}
