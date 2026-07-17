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
            $table->id('list_id');
            $table->unsignedBigInteger('wb_id');
            $table->uuid('list_index');
            $table->boolean('locked')->default(false);
            $table->boolean('public');
            $table->timestamps();
            $table->foreign('wb_id')
                ->references('wb_id')
                ->on('customer_equipment_workbooks')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workbook_task_lists', function (Blueprint $table) {
            $table->dropForeign(['wb_id']);
        });
        Schema::dropIfExists('workbook_task_lists');
    }
};
