<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_address', function (Blueprint $table) {
            $table->id(); // id auto increment
            $table->unsignedBigInteger('user_id'); // foreign key

            $table->string('receiver_name');
            $table->string('phone_number');
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('postal_code');
            $table->text('full_address');

            $table->boolean('is_default')->default(false);
            $table->timestamps();

            // FIX FOREIGN KEY
            $table->foreign('user_id')
                  ->references('user_id')   // REFER KE user_id YA, BUKAN id
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_address');
    }
};
