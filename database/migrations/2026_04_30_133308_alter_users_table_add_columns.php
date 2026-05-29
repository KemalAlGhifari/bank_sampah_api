<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom baru
            $table->foreignId('rt_id')->constrained('rts');  // Menambahkan foreign key ke tabel rts
            $table->decimal('total_kg', 10, 2)->default(0); // Kolom untuk total sampah
            $table->string('role')->default('user'); // Kolom untuk role pengguna
            $table->decimal('saldo', 10, 2)->default(0); // Kolom untuk saldo
            $table->string('phone')->nullable(); // Kolom untuk nomor telepon
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom yang baru ditambahkan saat rollback
            $table->dropColumn('rt_id');
            $table->dropColumn('total_kg');
            $table->dropColumn('saldo');
        });
    }
};
