<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EwalletTransferDetail;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EwalletTransferDetail>
 */
class EwalletTransferDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = EwalletTransferDetail::class;

    public function definition(): array
    {
         return [
            //
            'ewallet_provider_name' => $this->faker->randomElement(['Dana', 'OVO', 'Gopay']),
            'method_id' => 2,
            'phone_number' => $this->faker->phoneNumber(),
            'e_wallet_account_id' => 'LOOKSEE.ID',
        ];
    }
}
