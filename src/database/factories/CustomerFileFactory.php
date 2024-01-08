<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Models\FileUploads;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = CustomerFile::class;

    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'file_id' => FileUploads::factory(),
            'file_type_id' => CustomerFileType::inRandomOrder()->first()->file_type_id,
            'cust_equip_id' => null,
            'cust_id' => Customer::factory(),
            'user_id' => User::inRandomOrder()->first()->user_id,
            'name' => $this->faker->word(2),
        ];
    }
}
