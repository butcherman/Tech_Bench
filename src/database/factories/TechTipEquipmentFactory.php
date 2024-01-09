<?php

namespace Database\Factories;

use App\Models\TechTip;
use App\Models\Equipment;
use App\Models\TechTipEquipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class TechTipEquipmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = TechTipEquipment::class;

    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'tip_id' => TechTip::factory(),
            'equip_id' => Equipment::factory(),
        ];
    }
}
