<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePhoneNumberTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_number_types', function (Blueprint $table) {
            $table->id('phone_type_id');
            $table->text('description');
            $table->text('icon_class');
            $table->timestamps();
        });

        $defaultData = [
            ['phone_type_id' => 1, 'description' => 'Home',   'icon_class' => 'fas fa-home',       'created_at' => NOW(), 'updated_at' => NOW()],
            ['phone_type_id' => 2, 'description' => 'Work',   'icon_class' => 'fas fa-briefcase',  'created_at' => NOW(), 'updated_at' => NOW()],
            ['phone_type_id' => 3, 'description' => 'Mobile', 'icon_class' => 'fas fa-mobile-alt', 'created_at' => NOW(), 'updated_at' => NOW()],
        ];
        DB::table('phone_number_types')->insert($defaultData);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phone_number_types');
    }
}
