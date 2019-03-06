<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFileLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_links', function(Blueprint $table)
        {
            $table->integer('cust_id')
                ->unsigned()
                ->nullable()
                ->after('user_id');
            $table->longText('note')
                ->nullable()
                ->after('link_name');
            $table->foreign('cust_id')->references('cust_id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_links', function(Blueprint $table)
        {
            $table->dropForeign(['cust_id']);
            $table->dropColumn('cust_id');
            $table->dropColumn('note');
        });
    }
}
