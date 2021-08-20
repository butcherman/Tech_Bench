<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Support\Str;
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
            'name'      => $name = $this->faker->company(),
            'dba_name'  => null,
            'slug'      => Str::slug($name),
            'address'   => $this->faker->streetAddress(),
            'city'      => $this->faker->city(),
            'state'     => $this->faker->stateAbbr(),
            'zip'       => rand(20000, 99999),
        ];
    }
}
