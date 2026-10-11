<?php

namespace Database\Factories;

use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TechTipComment>
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
