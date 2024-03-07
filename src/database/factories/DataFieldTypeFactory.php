<?php

namespace Database\Factories;

use App\Models\DataFieldType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataFieldTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = DataFieldType::class;

    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'pattern' => null,
            'pattern_error' => null,
            'masked' => false,
            'is_hyperlink' => false,
            'allow_copy' => false,
        ];
    }
}
