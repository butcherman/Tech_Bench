<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('file_link_timelines', function (Blueprint $table) {
            $table->id('timeline_id');
            $table->unsignedBigInteger('link_id');
            $table->string('added_by');
            $table->timestamps();
            $table->foreign('link_id')
                ->references('link_id')
                ->on('file_links')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_link_timeline');
    }
};
