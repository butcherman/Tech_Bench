<?php

use App\Models\Notification;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $list = Notification::all();

        foreach ($list as $n) {
            $n->delete();
        }
    }
};
