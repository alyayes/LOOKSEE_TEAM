<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');            
            $table->unsignedBigInteger('user_id');            
            $table->unsignedBigInteger('address_id'); 
            $table->decimal('total_price', 15, 2);
            $table->decimal('shipping_cost', 15, 2)->default(0); 
            $table->decimal('grand_total', 15, 2);
            $table->string('status')->default('pending'); 
            $table->timestamp('order_date')->useCurrent();
            $table->string('shipping_method')->default('Regular Shipping');
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('user_address');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};