<?php

namespace Database\Factories;

use App\Models\CustomerContact;
use App\Models\PhoneNumberType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerContactPhone>
 */
class CustomerContactPhoneFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'cont_id' => CustomerContact::factory(),
            'phone_type_id' => PhoneNumberType::inRandomOrder()
                ->first()
                ->phone_type_id,
            'phone_number' => $this->faker->phoneNumber(),
            'extension' => null,
        ];
    }
}
