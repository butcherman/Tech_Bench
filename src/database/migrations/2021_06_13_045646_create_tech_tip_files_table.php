<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechTipFilesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up()
    {
        Schema::create('tech_tip_files', function (Blueprint $table) {
            $table->id('tip_file_id');
            $table->unsignedBigInteger('tip_id');
            $table->unsignedBigInteger('file_id');
            $table->timestamps();
            $table->foreign('tip_id')
                ->references('tip_id')
                ->on('tech_tips')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('file_id')
                ->references('file_id')
                ->on('file_uploads')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        Schema::table('tech_tip_files', function (Blueprint $table) {
            $table->dropForeign(['tip_id']);
            $table->dropForeign(['file_id']);
        });
        Schema::dropIfExists('tech_tip_files');
    }
}
