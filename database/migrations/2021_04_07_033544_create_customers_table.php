<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id('cust_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('name');
            $table->text('dba_name')->nullable();
            $table->text('slug');
            $table->text('address');
            $table->text('city');
            $table->text('state');
            $table->integer('zip');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('parent_id')->references('cust_id')->on('customers')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function(Blueprint $table)
        {
            $table->dropForeign(['parent_id']);
        });
        Schema::dropIfExists('customers');
    }
}
