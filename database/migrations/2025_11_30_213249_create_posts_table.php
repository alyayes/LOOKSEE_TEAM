<?php

// database/migrations/2025_11_30_213249_create_posts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            
            // PK: Ganti $table->id() agar menjadi INT Primary Key
            $table->integer('id_post')->autoIncrement()->primary(); 
            
            // FK User: Gunakan unsignedInteger agar cocok dengan users.user_id (INT)
            $table->unsignedInteger('user_id')->nullable(); 
            
            $table->string('image_post')->nullable();
            $table->text('caption')->nullable();
            $table->text('hashtags')->nullable();
            $table->enum('mood', ['Very Happy', 'Happy', 'Neutral', 'Sad', 'Very Sad'])->nullable();
            $table->integer('like_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->integer('share_count')->default(0);
            $table->timestamps();

            // Referensi FK: Merujuk ke PK users yang benar ('user_id')
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};