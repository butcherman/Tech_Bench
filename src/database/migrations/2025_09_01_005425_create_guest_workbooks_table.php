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
        Schema::create('guest_workbooks', function (Blueprint $table) {
            $table->unsignedBigInteger('guest_id');
            $table->unsignedBigInteger('wb_id');
            $table->timestamps();
            $table->foreign('guest_id')
                ->references('guest_id')
                ->on('guest_users')
                ->onUpdate('cascade');
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
        Schema::table('guest_workbooks', function (Blueprint $table) {
            $table->dropForeign(['guest_id']);
            $table->dropForeign(['wb_id']);
        });

        Schema::dropIfExists('guest_workbooks');
    }
};
