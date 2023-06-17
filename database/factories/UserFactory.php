<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
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
}
