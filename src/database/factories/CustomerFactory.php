<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerSite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'name' => $name = $this->faker->company(),
            'dba_name' => null,
            'slug' => Str::slug($name),
        ];
    }
}
