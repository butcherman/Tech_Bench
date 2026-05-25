<?php

namespace Database\Factories;

use App\Models\EquipmentType;
use App\Models\TechTip;
use App\Models\TechTipEquipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TechTipEquipment>
 */
class TechTipEquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'tip_id' => TechTip::factory(),
            'equip_id' => EquipmentType::factory(),
        ];
    }
}
