<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->bigIncrements('id_like'); // PK bigint auto_increment
            $table->unsignedBigInteger('id_post'); // harus sama tipe dengan posts.id_post
            $table->unsignedBigInteger('user_id'); // harus sama tipe dengan users.user_id
            $table->timestamp('created_at')->useCurrent();

            // Foreign key constraints
            $table->foreign('id_post')->references('id_post')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            $table->unique(['id_post', 'user_id']); // user hanya bisa like post sekali
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
