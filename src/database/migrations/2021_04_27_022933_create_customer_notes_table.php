<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerNotesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('customer_notes', function (Blueprint $table) {
            $table->id('note_id');
            $table->unsignedBigInteger('cust_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('urgent');
            $table->boolean('shared');
            $table->text('subject');
            $table->longText('details');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('cust_id')
                ->references('cust_id')
                ->on('customers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('created_by')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade');
            $table->foreign('updated_by')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('customer_notes', function (Blueprint $table) {
            $table->dropForeign(['cust_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });

        Schema::dropIfExists('customer_notes');
    }
}
