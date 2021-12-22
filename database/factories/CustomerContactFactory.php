<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerContact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cust_id' => Customer::factory()->create()->cust_id,
            'shared'  => $this->faker->boolean(),
            'name'    => $this->faker->name(),
            'email'   => $this->faker->email(),
        ];
    }
}
