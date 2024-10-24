<?php

namespace Database\Factories;

use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TechTipCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = TechTipComment::class;

    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'tip_id' => TechTip::factory(),
            'user_id' => User::inRandomOrder()->first()->user_id,
            'comment' => $this->faker->realText(),
        ];
    }
}
