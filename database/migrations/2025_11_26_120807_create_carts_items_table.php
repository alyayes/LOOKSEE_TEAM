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
        Schema::create('carts_items', function (Blueprint $table) {
            $table->id('cart_item_id');
            $table->integer('user_id');
            $table->unsignedBigInteger('id_produk');
            $table->integer('quantity')->default(1);
            $table->timestamp('added_at')->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk') ->on('produk_looksee')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts_items');
    }
};
