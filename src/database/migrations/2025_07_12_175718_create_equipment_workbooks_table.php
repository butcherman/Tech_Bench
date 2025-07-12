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
        Schema::create('equipment_workbooks', function (Blueprint $table) {
            $table->unsignedBigInteger('equip_id');
            $table->json('workbook_data')->nullable();
            $table->timestamps();
            $table->foreign('equip_id')
                ->references('equip_id')
                ->on('equipment_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_workbooks', function (Blueprint $table) {
            $table->dropForeign(['equip_id']);
        });
        Schema::dropIfExists('equipment_workbooks');
    }
};
