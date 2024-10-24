<?php

namespace Database\Factories;

use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataFieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = DataField::class;

    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'equip_id' => EquipmentType::factory(),
            'type_id' => DataFieldType::factory(),
            'order' => 0,
        ];
    }
}
