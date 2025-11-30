<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CartsItems;
use App\Models\User;
use App\Models\Produk;

class CartsItemsFactory extends Factory
{
    protected $model = CartsItems::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'id_produk' => Produk::inRandomOrder()->first()->id_produk ?? Produk::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'added_at' => now(),
        ];
    }
}
