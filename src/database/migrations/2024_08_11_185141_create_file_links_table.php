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
        if (!Schema::hasTable('file_links')) {
            Schema::create('file_links', function (Blueprint $table) {
                $table->id('link_id');
                $table->unsignedBigInteger('user_id');
                $table->text('link_hash');
                $table->text('link_name');
                $table->date('expire');
                $table->longText('instructions')->nullable();
                $table->boolean('allow_upload');
                $table->boolean('email_on_visit')->default(false);
                $table->timestamps();
                $table->foreign('user_id')
                    ->references('user_id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_links', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('file_link');
    }
};
