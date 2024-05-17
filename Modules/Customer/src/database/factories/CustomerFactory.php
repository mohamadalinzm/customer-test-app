<?php

namespace Customer\database\factories;

use Customer\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Symfony\Component\Uid\Ulid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ulid' => Ulid::generate(),
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'dateOfBirth' => $this->faker->date,
            'phoneNumber' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'bankAccountNumber' => $this->faker->creditCardNumber,
        ];
    }
}
