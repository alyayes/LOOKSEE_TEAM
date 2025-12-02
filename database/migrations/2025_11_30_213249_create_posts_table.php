<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
    $table->id(); 
    $table->unsignedBigInteger('user_id')->nullable(); 
    $table->string('image_post')->nullable();
    $table->text('caption')->nullable();
    $table->text('hashtags')->nullable();
    $table->enum('mood', ['Very Happy', 'Happy', 'Neutral', 'Sad', 'Very Sad'])->nullable();
    $table->integer('like_count')->default(0);
    $table->integer('comment_count')->default(0);
    $table->integer('share_count')->default(0);
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
