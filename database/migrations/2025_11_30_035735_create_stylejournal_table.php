<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('stylejournal', function (Blueprint $table) {
            $table->id('id_journal'); 
            $table->string('title', 255);
            $table->string('descr', 10000); 
            $table->longText('content')->nullable();
            $table->dateTime('publication_date')->nullable();
            $table->string('image', 100);

            $table->timestamps();
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('stylejournal');
    }
};

            
