<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_post_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_items', function (Blueprint $table) {
            $table->integer('id_post_items')->autoIncrement()->primary();
            
            // Foreign Keys (unsignedInteger)
            $table->unsignedInteger('id_post');
            $table->unsignedInteger('id_produk');
            
            // Constraints
            $table->foreign('id_post')->references('id_post')->on('posts')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('produk_looksee')->onDelete('cascade');
            
            // Jika ingin mencatat created_at
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_items');
    }
};