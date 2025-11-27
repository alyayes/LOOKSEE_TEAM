<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'profile_picture' => $this->faker->imageUrl(640, 480, 'people'),
            'password' => bcrypt('password'),
            'role' => $this->faker->randomElement(['admin', 'konsumen']),
            'bio' => $this->faker->text,
            'birthday' => $this->faker->date,
            'country' => $this->faker->country,
            'alamat' => $this->faker->address, 
            'phone' => $this->faker->phoneNumber,
            'twitter' => $this->faker->userName,
            'facebook' => $this->faker->userName,
            'instagram' => $this->faker->userName,
        ];
    }
}
