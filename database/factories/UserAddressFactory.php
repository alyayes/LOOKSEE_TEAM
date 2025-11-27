<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserAddress;
use App\Models\User;

class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('user_id') ?? 1,
            
            'receiver_name' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
            'province' => $this->faker->state(),
            'city' => $this->faker->city(),
            'district' => $this->faker->streetName(),
            'postal_code' => $this->faker->postcode(),
            'full_address' => $this->faker->address(),
            'is_default' => $this->faker->boolean(10),
        ];
    }
}