<?php

namespace Database\Factories;

use App\Models\TechTipType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TechTip>
 */
class TechTipFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->user_id,
            'tip_type_id' => TechTipType::inRandomOrder()->first()->tip_type_id,
            'sticky' => false,
            'subject' => $subject = $this->faker->realText(25),
            'slug' => Str::slug($subject),
            'details' => $this->faker->paragraph(5),
            'updated_id' => User::inRandomOrder()->first()->user_id,
            'public' => rand(1, 10) % 3 === 0 ? true : false,
        ];
    }
}
