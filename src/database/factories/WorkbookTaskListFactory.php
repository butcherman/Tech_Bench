<?php

namespace Database\Factories;

use App\Models\CustomerEquipmentWorkbook;
use App\Models\WorkbookTaskList;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<WorkbookTaskList>
 */
class WorkbookTaskListFactory extends Factory
{
    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'wb_id' => CustomerEquipmentWorkbook::factory(),
            'list_index' => Str::uuid(),
            'locked' => false,
            'public' => true,
        ];
    }
}
