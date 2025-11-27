<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        // Generate 20 produk dummy
        Produk::factory()->count(20)->create();
    }
}
