<?php

namespace Database\Factories;

use App\Models\CustomerEquipmentData;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerEquipmentDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerEquipmentData::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'value' => $this->faker->word(5),
        ];
    }
}
