<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserEmailNotifications;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserEmailNotificationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserEmailNotifications::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create(),
            'em_tech_tip' => 1,
            'em_file_link' => 1,
            'em_notification' => 1,
            'auto_del_link' => 1,
        ];
    }
}
