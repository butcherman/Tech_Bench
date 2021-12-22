<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Customer;
use App\Models\FileUploads;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerFile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'file_id'      => FileUploads::factory()->create()->file_id,
            'file_type_id' => CustomerFileType::inRandomOrder()->first()->file_type_id,
            'cust_id'      => Customer::factory()->create()->cust_id,
            'user_id'      => User::inRandomOrder()->first()->user_id,
            'shared'       => $this->faker->boolean(),
            'name'         => $this->faker->word(2),
        ];
    }
}
