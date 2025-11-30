<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('produk_looksee', function (Blueprint $table) {
        $table->string('preferensi')->after('kategori');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk_looksee', function (Blueprint $table) {
                    $table->dropColumn('preferensi');

        });
    }
};
