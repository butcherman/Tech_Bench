<?php

namespace Database\Factories;

use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Models\PhoneNumberType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerContactPhoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerContactPhone::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cont_id' => CustomerContact::factory()->create()->cont_id,
            'phone_type_id' => PhoneNumberType::inRandomOrder()->first()->phone_type_id,
            'phone_number' => $this->faker->phoneNumber(),
            'extension' => null,
        ];
    }
}
