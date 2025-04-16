<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->unsignedBigInteger('stock')->change(); // gunakan unsignedBigInteger jika stok tidak pernah negatif
        });
    }
    
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->integer('stock')->change();
        });
    }
    
};
