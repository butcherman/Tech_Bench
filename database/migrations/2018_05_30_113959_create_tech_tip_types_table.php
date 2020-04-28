<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechTipTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tech_tip_types', function(Blueprint $table) {
            $table->bigIncrements('tip_type_id');
            $table->text('description');
            $table->timestamps();
        });

        $tipTypeArr = [
            ['id' => 1, 'description' => 'Tech Tip'],
            ['id' => 2, 'description' => 'Documentation'],
            ['id' => 3, 'description' => 'Software'],
        ];

        foreach($tipTypeArr as $type)
        {
            DB::insert('INSERT INTO `tech_tip_types` (`tip_type_id`, `description`, `created_at`, `updated_at`)
                        VALUES (?, ?, ?, ?)',
                        [$type['id'], $type['description'], NOW(), NOW()]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tech_tip_types');
    }
}
