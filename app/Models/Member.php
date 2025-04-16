<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';

    protected $fillable = [
        'nama_member', 'no_hp', 'tanggal_daftar', 'point'
    ];

    public function transaksi() {
        return $this->hasMany(Transaksi::class);
    }

}
