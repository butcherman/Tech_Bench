<?php

use App\TechTipTypes;
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

        $defaultData = [
            ['tip_type_id' => 1, 'description' => 'Tech Tip',      'created_at' => NOW(), 'updated_at' => NOW()],
            ['tip_type_id' => 2, 'description' => 'Documentation', 'created_at' => NOW(), 'updated_at' => NOW()],
            ['tip_type_id' => 3, 'description' => 'Software',      'created_at' => NOW(), 'updated_at' => NOW()],
        ];
        // TechTipTypes::insert($defaultData);
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
