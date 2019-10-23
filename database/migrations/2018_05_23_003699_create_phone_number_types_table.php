<?php

use App\PhoneNumberTypes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhoneNumberTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_number_types', function(Blueprint $table) {
            $table->increments('phone_type_id');
            $table->text('description');
            $table->text('icon_class');
            $table->timestamps();
        });
        
        //  Create Default Data
        PhoneNumberTypes::create([
            'phone_type_id' => 1,
            'description'   => 'Home',
            'icon_class'    => 'ti-home'
        ]);
        PhoneNumberTypes::create([
            'phone_type_id' => 2,
            'description'   => 'Work',
            'icon_class'    => 'ti-briefcase'
        ]);
        PhoneNumberTypes::create([
            'phone_type_id' => 3,
            'description'   => 'Mobile',
            'icon_class'    => 'ti-mobile'
        ]);
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
