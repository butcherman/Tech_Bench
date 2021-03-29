<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatesForVersion60 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  TODO - Migrate System_categories to equipment_categories
        //  TODO - Migrate System_types to equipment_types
        //  TODO - remove folder location column from equipment types table
        //  TODO - Migrate system data fields to equipment data fields
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
