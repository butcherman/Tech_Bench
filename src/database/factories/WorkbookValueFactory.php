<?php

namespace Database\Factories;

use App\Models\CustomerEquipmentWorkbook;
use App\Models\WorkbookValue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<WorkbookValue>
 */
class WorkbookValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wb_id' => CustomerEquipmentWorkbook::factory(),
            'table_index' => Str::uuid(),
            'row_index' => Str::uuid(),
            'column_name' => $this->faker()->word(),
            'value' => $this->faker()->words(2),
            'protected' => false,
        ];
    }
}
