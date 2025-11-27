<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('user_id')->autoIncrement(); // Primary key

            $table->string('username', 50)->unique();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('profile_picture', 255)->nullable();
            $table->string('password', 255);

            $table->enum('role', ['admin', 'konsumen'])->default('konsumen');

            $table->text('bio')->nullable();
            $table->date('birthday')->nullable();
            $table->string('country', 100)->nullable();

            $table->string('alamat', 255)->nullable();

            $table->string('phone', 20)->nullable();
            $table->string('twitter', 100)->nullable();
            $table->string('facebook', 100)->nullable();
            $table->string('instagram', 100)->nullable();
            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
