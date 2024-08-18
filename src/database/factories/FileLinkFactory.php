<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FileLink>
 */
class FileLinkFactory extends Factory
{
    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'link_hash' => Str::uuid(),
            'link_name' => $this->faker->realText(10),
            'expire' => Carbon::now()->addDays(90),
            'instructions' => $this->faker->paragraph(5),
            'allow_upload' => false,
            'email_on_visit' => false,
        ];
    }
}
