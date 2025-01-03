<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTechTipTypesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('tech_tip_types', function (Blueprint $table) {
            $table->id('tip_type_id');
            $table->text('description');
            $table->timestamps();
        });

        /**
         * Default Data
         */
        $defaultData = [
            [
                'tip_type_id' => 1,
                'description' => 'Tech Tip',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'tip_type_id' => 2,
                'description' => 'Documentation',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'tip_type_id' => 3,
                'description' => 'Software',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
        ];

        DB::table('tech_tip_types')->insert($defaultData);
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_tip_types');
    }
}
