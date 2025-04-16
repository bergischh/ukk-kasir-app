<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */public function up(): void
        {
            Schema::table('transaksi', function (Blueprint $table) {
                $table->foreignId('id_member')->nullable()->change();
            });
        }

        public function down(): void
        {
            Schema::table('transaksi', function (Blueprint $table) {
                $table->foreignId('id_member')->nullable(false)->change();
            });
        }

};
