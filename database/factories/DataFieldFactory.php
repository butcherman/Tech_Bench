<?php

namespace Database\Factories;

use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataFieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DataField::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'equip_id' => EquipmentType::factory()->create()->equip_id,
            'type_id' => DataFieldType::factory()->create()->type_id,
            'order' => 0,
        ];
    }
}
