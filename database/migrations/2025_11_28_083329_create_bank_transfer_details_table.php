<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_transfer_details', function (Blueprint $table) {
            $table->id('bank_payment_id');            
            $table->string('bank_name'); 
            $table->unsignedBigInteger('method_id');
            $table->string('account_number');
            $table->string('account_holder_name');
            $table->timestamps();
            $table->foreign('method_id')
                  ->references('method_id')
                  ->on('payment_methods')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_transfer_details');
    }
};