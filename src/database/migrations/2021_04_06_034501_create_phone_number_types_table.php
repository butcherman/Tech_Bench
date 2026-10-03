<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneNumberTypesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('phone_number_types', function (Blueprint $table) {
            $table->id('phone_type_id');
            $table->text('description');
            $table->text('icon_class');
            $table->timestamps();
        });
    }
}
