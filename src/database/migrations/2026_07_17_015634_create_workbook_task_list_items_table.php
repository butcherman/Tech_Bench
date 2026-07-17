<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workbook_task_list_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('list_id');
            $table->text('list_item');
            $table->integer('order');
            $table->timestamp('completed')->nullable();
            $table->text('completed_by')->nullable();
            $table->unsignedBigInteger('file_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('list_id')
                ->references('list_id')
                ->on('workbook_task_lists')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('file_id')
                ->references('file_id')
                ->on('file_uploads')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workbook_task_list_items', function (Blueprint $table) {
            $table->dropForeign(['list_id']);
            $table->dropForeign(['file_id']);
        });
        Schema::dropIfExists('workbook_task_list_items');
    }
};
