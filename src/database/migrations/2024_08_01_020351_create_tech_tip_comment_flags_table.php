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
        // Rename primary key column for Tech Tip Comments & drop flagged column
        Schema::table('tech_tip_comments', function (Blueprint $table) {
            $table->renameColumn('id', 'comment_id');
            $table->dropColumn('flagged');
        });

        Schema::create('tech_tip_comment_flags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->unique(['user_id', 'comment_id']);
            $table->foreign('comment_id')
                ->references('comment_id')
                ->on('tech_tip_comments')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_tip_comment_flags');
    }
};
