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
        Schema::create('likes', function (Blueprint $table) {
            // Primary Key sesuai struktur (id_like: int(11) auto_increment)
            $table->integer('id_like')->autoIncrement()->primary();

            // Foreign Key ke Post (int(11))
            $table->unsignedInteger('id_post');
            
            // Foreign Key ke User (int(11))
            $table->unsignedInteger('user_id');
            
            // Kolom created_at (timestamp)
            $table->timestamp('created_at')->useCurrent(); 

            // Constraints (Kunci Asing)
            $table->foreign('id_post')->references('id_post')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            
            // Opsional: Pastikan user hanya bisa like post sekali
            $table->unique(['id_post', 'user_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};