<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id_comment'); // PK bigint auto_increment
            $table->unsignedBigInteger('id_post'); // FK ke posts.id_post
            $table->unsignedBigInteger('user_id'); // FK ke users.user_id
            $table->text('comment_text');
            $table->timestamp('created_at')->useCurrent();

            // Foreign key constraints
            $table->foreign('id_post')->references('id_post')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
