<?php

namespace Database\Factories;

use App\Classes\Generator;
use App\Classes\Mutator;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     * @return array
     */
    public function definition()
    {
        return [
            'identification_number' => $this->faker->tcNo,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'telephone' => Mutator::phone($this->faker->phoneNumber),
            'email' => $this->faker->email,
            'customer_no' => Generator::customerNo(),
            'reference_code' => Generator::referenceCode(),
            'type' => 2
        ];
    }
}
