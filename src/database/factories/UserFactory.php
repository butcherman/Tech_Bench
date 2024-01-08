<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = User::class;

    /**
     * Define the model's default state
     */
    public function definition()
    {
        $first = $this->faker->firstName();
        $last = $this->faker->lastName();

        return [
            'role_id' => 4,
            'username' => $first.'.'.$last,
            'first_name' => $first,
            'last_name' => $last,
            'email' => $first.'.'.$last.'@noEm.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'password_expires' => null,
        ];
    }

    /**
     * Automatically create the User Settings after adding them to DB
     */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            $settingData = UserSettingType::all();

            foreach ($settingData as $setting) {
                UserSetting::create([
                    'user_id' => $user->user_id,
                    'setting_type_id' => $setting->setting_type_id,
                    'value' => false,
                ]);
            }
        });
    }
}
