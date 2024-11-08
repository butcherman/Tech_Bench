<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerSite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $name = $this->faker->company(),
            'dba_name' => null,
            'slug' => Str::slug($name),
        ];
    }

    /**
     * Create a customer Site for the Primary Site ID field
     */
    public function configure()
    {
        return $this->afterCreating(function (Customer $customer) {
            $site = CustomerSite::factory()->create(['cust_id' => $customer->cust_id]);
            $customer->primary_site_id = $site->cust_site_id;
            $customer->save();
        });
    }
}
