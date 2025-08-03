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
        Schema::create('customer_workbook_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wb_id');
            $table->uuid('index');
            $table->longText('value')->nullable();
            $table->timestamps();
            $table->foreign('wb_id')
                ->references('wb_id')
                ->on('customer_workbooks')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_workbook_values');
    }
};
