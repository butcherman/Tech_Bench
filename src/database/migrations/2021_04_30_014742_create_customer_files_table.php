<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerFilesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('customer_files', function (Blueprint $table) {
            $table->id('cust_file_id');
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('file_type_id');
            $table->unsignedBigInteger('cust_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shared')->default(0);
            $table->text('name');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('file_id')
                ->references('file_id')
                ->on('file_uploads')
                ->onUpdate('cascade');
            $table->foreign('file_type_id')
                ->references('file_type_id')
                ->on('customer_file_types')
                ->onUpdate('cascade');
            $table->foreign('cust_id')
                ->references('cust_id')
                ->on('customers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('user_id')
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
        Schema::table('customer_files', function (Blueprint $table) {
            $table->dropForeign(['file_id']);
            $table->dropForeign(['file_type_id']);
            $table->dropForeign(['cust_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('customer_files');
    }
}
