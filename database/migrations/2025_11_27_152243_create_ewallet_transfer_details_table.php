<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
        * Run the migrations.
        */
        Schema::create('ewallet_transfer_details', function (Blueprint $table) {
            $table->id('e_wallet_payment_id');
            
            $table->string('ewallet_provider_name');             
            $table->unsignedBigInteger('method_id');
            $table->string('phone_number');
            $table->string('e_wallet_account_id')->default('LOOKSEE.ID');
            $table->timestamps();

            $table->foreign('method_id')
                  ->references('method_id')
                  ->on('payment_methods')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ewallet_transfer_details');
    }
};