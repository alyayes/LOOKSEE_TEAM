<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Produk;

class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    public function definition(): array
    {
        return [
            'gambar_produk' => $this->faker->imageUrl(640, 480, 'fashion'), 
            'nama_produk'   => $this->faker->words(3, true),              
            'deskripsi'     => $this->faker->paragraph(),                   
            'harga'         => $this->faker->numberBetween(40000, 100000),  
            'kategori'      => $this->faker->randomElement([
                'Man',
                'Woman',
            ]),
            'preferensi'    => $this->faker->randomElement([
                'Casual',
                'Formal',
                'Streetwear',
                'Sporty',
                'Vintage',
                'Minimalist',
            ]),
            'mood'          => $this->faker->randomElement([
                'Very Sad',
                'Sad',
                'Neutral',
                'Happy',
                'Very Happy',
            ]),
            'stock'         => $this->faker->numberBetween(1, 100),
        ];
    }
}