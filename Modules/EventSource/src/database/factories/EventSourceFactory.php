<?php

namespace EventSource\database\factories;

use Customer\Models\Customer;
use EventSource\Enums\ActionEnum;
use EventSource\Models\EventSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\EventSource>
 */
class EventSourceFactory extends Factory
{
    protected $model = EventSource::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $action = $this->faker->randomElement(ActionEnum::values());
        //if action is store, then return all fields if action is update then return only one field if action is delete then return only customerId
        if ($action === ActionEnum::STORE->value) {
            $request_body = [
                'firstname' => $this->faker->firstName,
                'lastname' => $this->faker->lastName,
                'dateOfBirth' => $this->faker->date,
                'phoneNumber' => $this->faker->phoneNumber,
                'email' => $this->faker->email,
                'bankAccountNumber' => $this->faker->creditCardNumber,
            ];

        }

        if ($action === ActionEnum::UPDATE->value) {
            $request_body = $this->faker->randomElement([
                'customerId' => Customer::query()->inRandomOrder()->first()->id,
                'firstname' => $this->faker->firstName,
                'lastname' => $this->faker->lastName,
                'dateOfBirth' => $this->faker->date,
                'phoneNumber' => $this->faker->phoneNumber,
                'email' => $this->faker->email,
                'bankAccountNumber' => $this->faker->creditCardNumber,
            ]);
        }

        if ($action === ActionEnum::DELETE->value) {
            $request_body = $this->faker->randomElement([
                'customerId' => Customer::query()->inRandomOrder()->first()->id,
            ]);
        }

        return [
            //choose random from an array of names
            'action' => $action,
            'request_body' => $request_body,
        ];
    }
}
