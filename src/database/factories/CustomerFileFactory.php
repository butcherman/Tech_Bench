<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerFile>
 */
class CustomerFileFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'file_id' => FileUpload::factory(),
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'cust_id' => Customer::factory(),
            'user_id' => User::inRandomOrder()->first()->user_id,
            'name' => $this->faker->word(),
        ];
    }
}
