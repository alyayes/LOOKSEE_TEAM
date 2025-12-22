<?php

// database/migrations/2025_12_02_033500_create_comments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('id_comment'); // Primary Key (BIGINT)

            // FK Post: Gunakan unsignedInteger (INT) agar cocok dengan posts.id_post (INT)
            $table->unsignedInteger('id_post'); 
            // Referensi PK posts yang benar ('id_post')
            $table->foreign('id_post')->references('id_post')->on('posts')->onDelete('cascade'); 


            // FK User: Gunakan unsignedInteger (INT) agar cocok dengan users.user_id (INT)
            $table->unsignedInteger('user_id'); 
            // Referensi PK users yang benar ('user_id')
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade'); 


            $table->text('comment_text');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};