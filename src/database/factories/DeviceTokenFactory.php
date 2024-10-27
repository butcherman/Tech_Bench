<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeviceToken>
 */
class DeviceTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'token' => Str::random(60),
            'type' => $this->faker
                ->randomElement(['desktop', 'mobile', 'smartphone', 'tablet']),
            'os' => $this->faker
                ->randomElement(['Ubuntu', 'Android', 'Windows', 'Mac', 'iOS']),
            'browser' => $this->faker
                ->randomElement(['Chrome', 'IE', 'Safari', 'Firefox']),
            'registered_ip_address' => '192.168.1.'.rand(1, 254),
            'updated_ip_address' => '192.168.1.'.rand(1, 254),
        ];
    }
}
