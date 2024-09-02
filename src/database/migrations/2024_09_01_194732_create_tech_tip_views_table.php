<?php

use App\Models\TechTip;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tech_tip_views', function (Blueprint $table) {
            $table->unsignedBigInteger('tip_id')->unique();
            $table->integer('views')->default(0);
            $table->timestamps();
        });

        // Move all current Views from tech_tips table
        $tips = TechTip::all();
        foreach ($tips as $tip) {
            DB::table('tech_tip_views')->insert([
                'tip_id' => $tip->tip_id,
                'views' => $tip->views,
                'created_at' => $tip->created_at,
                'updated_at' => now(),
            ]);
        }

        // Remove the views column from the tech_tips table
        Schema::table('tech_tips', function (Blueprint $table) {
            $table->dropColumn('views');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_tip_views');
    }
};
