<?php

use App\SystemDataFieldTypes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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

        //  Enter default data
        $defaultData = [
            ['data_type_id' => 1, 'name' => 'IP Address',      'hidden' => false, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['data_type_id' => 2, 'name' => 'Version',         'hidden' => false, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['data_type_id' => 3, 'name' => 'Login Username',  'hidden' => false, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['data_type_id' => 4, 'name' => 'Login Password',  'hidden' => false, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['data_type_id' => 5, 'name' => 'Remote Access',   'hidden' => false, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['data_type_id' => 6, 'name' => 'Subnet Mask',     'hidden' => false, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['data_type_id' => 7, 'name' => 'Default Gateway', 'hidden' => false, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['data_type_id' => 8, 'name' => 'Primary DNS',     'hidden' => false, 'created_at' => NOW(), 'updated_at' => NOW()],
            ['data_type_id' => 9, 'name' => 'Secondary DNS',   'hidden' => false, 'created_at' => NOW(), 'updated_at' => NOW()],
        ];
        SystemDataFieldTypes::insert($defaultData);
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
