<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerFileTypesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('customer_file_types', function (Blueprint $table) {
            $table->id('file_type_id');
            $table->text('description');
            $table->timestamps();
        });
    }
}
