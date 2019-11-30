<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\TechTipTypes;

class CreateTechTipTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tech_tip_types', function (Blueprint $table) {
            $table->bigIncrements('tip_type_id');
            $table->text('description');
            $table->timestamps();
        });

        //  TODO - Simplify this
        TechTipTypes::create(['description' => 'Tech Tip']);
        TechTipTypes::create(['description' => 'Documentation']);
        TechTipTypes::create(['description' => 'Software']);
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
