<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts_items', function (Blueprint $table) {
            $table->id();

            // FK ke users.user_id (bukan id!)
            $table->unsignedBigInteger('user_id');

            // FK ke produk_looksee.id_produk
            $table->unsignedBigInteger('product_id');

            $table->integer('quantity')->default(1);
            $table->timestamps();

            // Foreign key yang benar
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id_produk')
                ->on('produk_looksee')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts_items');
    }
};
