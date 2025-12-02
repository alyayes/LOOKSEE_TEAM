<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorite', function (Blueprint $table) {
            $table->increments('id_fav'); // primary key sesuai struktur kamu
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_produk');
            $table->timestamp('created_at')->useCurrent();

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('id_produk')->references('id_produk')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
