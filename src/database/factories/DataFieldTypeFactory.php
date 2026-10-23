<?php

namespace Database\Factories;

use App\Models\DataFieldType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DataFieldType>
 */
class DataFieldTypeFactory extends Factory
{
    /**
     * Define the model's default state.
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
            'do_not_log_value' => false,
            'masked' => false,
        ];
    }
}
