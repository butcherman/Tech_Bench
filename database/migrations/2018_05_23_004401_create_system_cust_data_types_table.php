<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SystemCustDataTypes;

class CreateSystemCustDataTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_cust_data_types', function (Blueprint $table) {
            $table->increments('data_type_id');
            $table->string('name');
            $table->timestamps();
        });
        
        //  Enter default data
        SystemCustDataTypes::create([
            'data_type_id' => 1,
            'name'         => 'IP Address'
        ]);
        SystemCustDataTypes::create([
            'data_type_id' => 2,
            'name'         => 'Version'
        ]);
        SystemCustDataTypes::create([
            'data_type_id' => 3,
            'name'         => 'Login Username'
        ]);
        SystemCustDataTypes::create([
            'data_type_id' => 4,
            'name'         => 'Login Password'
        ]);
        SystemCustDataTypes::create([
            'data_type_id' => 5,
            'name'         => 'Remote Access'
        ]);
        SystemCustDataTypes::create([
            'data_type_id' => 6,
            'name'         => 'Subnet Mask'
        ]);
        SystemCustDataTypes::create([
            'data_type_id' => 7,
            'name'         => 'Default Gateway'
        ]);
        SystemCustDataTypes::create([
            'data_type_id' => 8,
            'name'         => 'Primary DNS'
        ]);
        SystemCustDataTypes::create([
            'data_type_id' => 9,
            'name'         => 'Secondary DNS'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_cust_data_types');
    }
}
