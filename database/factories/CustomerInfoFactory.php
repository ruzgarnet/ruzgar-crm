<?php

namespace Database\Factories;

use App\Classes\Mutator;
use App\Models\CustomerInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerInfoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerInfo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'gender' => rand(1, 2),
            'secondary_telephone' => Mutator::phone($this->faker->phoneNumber),
            'birthday' => $this->faker->dateTimeBetween('-50 years', '-20 years'),
            'city_id' => rand(1, 81),
            'district_id' => rand(1, 973),
            'address' => $this->faker->address
        ];
    }
}
