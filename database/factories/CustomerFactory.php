<?php

namespace Database\Factories;

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
            'parent_id' => null,
            'name'      => $this->faker->company(),
            'dba_name'  => null,
            'address'   => $this->faker->streetAddress(),
            'city'      => $this->faker->city(),
            'state'     => $this->faker->stateAbbr(),
            'zip'       => rand(20000, 99999),
        ];
    }
}
