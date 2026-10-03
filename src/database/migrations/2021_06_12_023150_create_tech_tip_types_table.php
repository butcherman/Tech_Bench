<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechTipTypesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('tech_tip_types', function (Blueprint $table) {
            $table->id('tip_type_id');
            $table->text('description');
            $table->timestamps();
        });
    }
}
