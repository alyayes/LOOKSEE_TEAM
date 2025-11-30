<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_payment', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('method_id'); 
            $table->decimal('amount', 15, 2);
            $table->timestamp('payment_date')->useCurrent();
            $table->string('transaction_status'); 
            $table->string('transaction_code');  
            $table->unsignedBigInteger('bank_payment_id_fk')->nullable();
            $table->unsignedBigInteger('e_wallet_payment_id_fk')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('method_id')->references('method_id')->on('payment_methods');
            $table->foreign('bank_payment_id_fk')->references('bank_payment_id')->on('bank_transfer_details');
            $table->foreign('e_wallet_payment_id_fk')->references('e_wallet_payment_id')->on('ewallet_transfer_details');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_payment');
    }
};