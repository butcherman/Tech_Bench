<?php

namespace Database\Factories;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EquipmentType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cat_id' => EquipmentCategory::factory()->create(),
            'name'   => $this->faker->unique()->word(1),
        ];
    }
}
