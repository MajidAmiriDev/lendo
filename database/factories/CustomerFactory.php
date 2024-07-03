<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
        /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bank_account_number' => $this->faker->bankAccountNumber,
            'status' => 'CHECK_HAVING_ACCOUNT',
            'complete_info' => true,
            'mobile' => $this->faker->phoneNumber,
            'name' => $this->faker->name,
        ];
    }
}
