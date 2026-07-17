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
        Schema::create('workbook_task_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wb_id');
            $table->uuid('list_index');
            $table->text('list_item');
            $table->integer('order');
            $table->timestamp('completed');
            $table->text('completed_by');
            $table->unsignedBigInteger('file_id');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('wb_id')
                ->references('wb_id')
                ->on('customer_equipment_workbooks')
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
        Schema::table('workbook_task_lists', function (Blueprint $table) {
            $table->dropForeign(['wb_id']);
            $table->dropForeign(['file_id']);
        });
        Schema::dropIfExists('workbook_task_lists');
    }
};
