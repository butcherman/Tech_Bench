<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDataFieldTypesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up()
    {
        Schema::create('data_field_types', function (Blueprint $table) {
            $table->id('type_id');
            $table->string('name');
            $table->boolean('hidden')->default(0);
            $table->timestamps();
        });

        /**
         * Default Data
         */
        $defaultData = [
            [
                'type_id' => 1,
                'name' => 'IP Address',
                'hidden' => false,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'type_id' => 2,
                'name' => 'Version',
                'hidden' => false,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'type_id' => 3,
                'name' => 'Login Username',
                'hidden' => false,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'type_id' => 4,
                'name' => 'Login Password',
                'hidden' => false,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'type_id' => 5,
                'name' => 'Remote Access',
                'hidden' => false,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'type_id' => 6,
                'name' => 'Subnet Mask',
                'hidden' => false,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'type_id' => 7,
                'name' => 'Default Gateway',
                'hidden' => false,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'type_id' => 8,
                'name' => 'Primary DNS',
                'hidden' => false,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'type_id' => 9,
                'name' => 'Secondary DNS',
                'hidden' => false,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
        ];
        DB::table('data_field_types')->insert($defaultData);
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        Schema::dropIfExists('data_field_types');
    }
}
