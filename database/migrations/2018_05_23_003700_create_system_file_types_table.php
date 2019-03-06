<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SystemFileTypes;

class CreateSystemFileTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_file_types', function (Blueprint $table) {
            $table->increments('type_id');
            $table->string('description');
            $table->timestamps();
        });
        
        //  Enter default data
        SystemFileTypes::create([
            'type_id'     => 1,
            'description' => 'Manuals'
        ]);
        SystemFileTypes::create([
            'type_id'     => 2,
            'description' => 'Notes'
        ]);
        SystemFileTypes::create([
            'type_id'     => 3,
            'description' => 'Handouts'
        ]);
        SystemFileTypes::create([
            'type_id'     => 4,
            'description' => 'Firmware'
        ]);
        SystemFileTypes::create([
            'type_id'     => 5,
            'description' => 'Software'
        ]);
        SystemFileTypes::create([
            'type_id'     => 6,
            'description' => 'User Guides'
        ]);
        SystemFileTypes::create([
            'type_id'     => 7,
            'description' => 'Brochures'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_file_types');
    }
}
