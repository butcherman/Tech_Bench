<?php

namespace Database\Factories;

use App\Models\BackupRun;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BackupRun>
 */
class BackupRunFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'backup_name' => 'backup-'.now()->format('Y-m-d-h-m-s'),
            'type' => 'unknown',
            'status' => 'completed',
            'started_at' => now(),
            'completed_at' => now(),
            'size' => 123456,
            'error' => null,
        ];
    }
}
