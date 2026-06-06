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
        Schema::create('workbook_table_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wb_id');
            $table->uuid('table_index');
            $table->uuid('row_index');
            $table->text('column_name');
            $table->longText('value')->nullable();
            $table->boolean('public')->default(false);
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
        Schema::table('workbook_table_values', function (Blueprint $table) {
            $table->dropForeign(['wb_id']);
        });
        Schema::dropIfExists('workbook_table_values');
    }
};
