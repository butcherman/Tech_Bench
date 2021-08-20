<?php

namespace Database\Factories;

use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TechTipCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TechTipComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tip_id'  => TechTip::factory()->create()->tip_id,
            'user_id' => User::factory()->create()->user_id,
            'comment' => $this->faker->realText(),
        ];
    }
}
