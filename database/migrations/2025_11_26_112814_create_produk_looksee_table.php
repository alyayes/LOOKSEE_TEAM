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

            // Gambar utama produk
            $table->text('gambar_produk');

            // Nama & deskripsi
            $table->string('nama_produk', 255);
            $table->longText('deskripsi')->nullable();

            // Harga
            $table->double('harga');

            // Mood (Happy, Formal, Cozy, Energetic, Calm)
            $table->string('mood', 20);

            // Kategori (Outer, Dress, Jeans, dll)
            $table->string('kategori', 255);

            // Gender (Man/Woman/Unisex)
            $table->string('gender', 20)->nullable();

            // Marketplace (Shopee, Tokopedia, dll)
            $table->string('platform', 50)->nullable();

            // Link produk ke marketplace
            $table->string('link_produk', 500)->nullable();

            // Stok produk
            $table->integer('stock');

            // Timestamp created_at & updated_at
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
