<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemCustDataTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_cust_data_types', function(Blueprint $table) 
        {
            $table->boolean('hidden')
                ->default(0)
                ->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_cust_data_types', function(Blueprint $table)
        {
            $table->dropColumn('hidden');
        });
    }
}
