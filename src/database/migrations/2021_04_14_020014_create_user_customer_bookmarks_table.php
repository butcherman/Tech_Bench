<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCustomerBookmarksTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('user_customer_bookmarks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cust_id');
            $table->timestamps();
            $table->unique(['user_id', 'cust_id']);
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('cust_id')
                ->references('cust_id')
                ->on('customers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('user_customer_bookmarks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['cust_id']);
        });

        Schema::dropIfExists('user_customer_bookmarks');
    }
}
