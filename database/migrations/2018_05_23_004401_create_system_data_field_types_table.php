<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SystemDataFieldTypes;

class CreateSystemDataFieldTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_data_field_types', function(Blueprint $table) {
            $table->increments('data_type_id');
            $table->string('name');
            $table->boolean('hidden')->default(0);
            $table->timestamps();
        });

        //  TODO - simplify this
        //  Enter default data
        SystemDataFieldTypes::create([
            'data_type_id' => 1,
            'name'         => 'IP Address'
        ]);
        SystemDataFieldTypes::create([
            'data_type_id' => 2,
            'name'         => 'Version'
        ]);
        SystemDataFieldTypes::create([
            'data_type_id' => 3,
            'name'         => 'Login Username'
        ]);
        SystemDataFieldTypes::create([
            'data_type_id' => 4,
            'name'         => 'Login Password'
        ]);
        SystemDataFieldTypes::create([
            'data_type_id' => 5,
            'name'         => 'Remote Access'
        ]);
        SystemDataFieldTypes::create([
            'data_type_id' => 6,
            'name'         => 'Subnet Mask'
        ]);
        SystemDataFieldTypes::create([
            'data_type_id' => 7,
            'name'         => 'Default Gateway'
        ]);
        SystemDataFieldTypes::create([
            'data_type_id' => 8,
            'name'         => 'Primary DNS'
        ]);
        SystemDataFieldTypes::create([
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
