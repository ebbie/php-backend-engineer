<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    protected $model = \App\Models\Loan::class;

    public function definition()
    {
        return [
            'amount' => $this->faker->randomFloat(2, 1000, 50000),
            'interest_rate' => $this->faker->randomFloat(2, 1, 20),
            'duration_in_months' => $this->faker->numberBetween(6, 60),
            'lender_id' => User::factory(), // Assuming the lender is a user
            'borrower_id' => User::factory(), // Assuming the borrower is a user
        ];
    }
}

