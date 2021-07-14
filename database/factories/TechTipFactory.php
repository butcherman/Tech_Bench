<?php

namespace Database\Factories;

use App\Models\TechTip;
use App\Models\TechTipType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class TechTipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TechTip::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'     => User::factory()->create()->user_id,
            'tip_type_id' => TechTipType::inRandomOrder()->first()->tip_type_id,
            'sticky'      => false,
            'subject'     => $subject = $this->faker->realText(25),
            'slug'        => Str::slug($subject),
            'details'     => $this->faker->paragraph(5),
        ];
    }
}
