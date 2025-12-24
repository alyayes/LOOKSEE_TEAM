<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('produk_looksee', function (Blueprint $table) {
        $table->id('id_produk');
        $table->text('gambar_produk');
        $table->string('nama_produk', 255);
        $table->longText('deskripsi')->nullable();
        $table->double('harga');
        $table->string('kategori', 255);
        $table->string('mood', 20);
        $table->integer('stock');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_looksee');
    }
};