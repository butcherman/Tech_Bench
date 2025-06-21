<?php

namespace Database\Factories;

use App\Models\TechTip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TechTipComment>
 */
class TechTipCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'tip_id' => TechTip::factory(),
            'user_id' => User::factory(),
            'comment' => $this->faker->realText(),
        ];
    }
}
