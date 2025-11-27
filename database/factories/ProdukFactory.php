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
            'harga'         => $this->faker->numberBetween(50000, 500000),  
            'kategori'      => $this->faker->randomElement([
                'man',
                'woman',
            ]),
            'mood'          => $this->faker->randomElement([
                'very sad',
                'sad',
                'neutral',
                'happy',
                'very happy',
            ]),
            'stock'         => $this->faker->numberBetween(1, 100),
        ];
    }
}
