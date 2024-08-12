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
        if (!Schema::hasTable('file_link_files')) {
            Schema::create('file_link_files', function (Blueprint $table) {
                $table->id('link_file_id');
                $table->unsignedBigInteger('link_id');
                $table->unsignedBigInteger('file_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->text('added_by')->nullable();
                $table->boolean('upload');
                $table->longText('note')->nullable();
                $table->timestamps();
                $table->foreign('link_id')
                    ->references('link_id')
                    ->on('file_links')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
                $table->foreign('file_id')
                    ->references('file_id')
                    ->on('file_uploads')
                    ->onUpdate('cascade');
                $table->foreign('user_id')
                    ->references('user_id')
                    ->on('users')
                    ->onUpdate('cascade');
            });
        }

        Schema::table('file_link_files', function (Blueprint $table) {
            $table->boolean('upload')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_link_files', function (Blueprint $table) {
            $table->dropForeign(['link_id']);
            $table->dropForeign(['file_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('file_link_files');
    }
};
