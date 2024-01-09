<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileUploadsTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up()
    {
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id('file_id');
            $table->string('disk');
            $table->string('folder');
            $table->string('file_name');
            $table->boolean('public')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        Schema::dropIfExists('file_uploads');
    }
}
