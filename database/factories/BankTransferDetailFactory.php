<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankTransferDetail>
 */
class BankTransferDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bank_name' => $this->faker->creditCardType,
            'method_id' => 1, 
            'account_number' => $this->faker->bankAccountNumber,
            'account_holder_name' => 'PT. LOOKSEE INDONESIA',
        ];
    }
}
