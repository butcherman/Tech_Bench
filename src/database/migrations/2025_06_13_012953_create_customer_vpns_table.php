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
        Schema::create('customer_vpns', function (Blueprint $table) {
            $table->id('vpn_id');
            $table->text('vpn_client_name');
            $table->text('vpn_portal_url');
            $table->text('vpn_username')->nullable();
            $table->text('vpn_password')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('vpn_id')->nullable()->after('slug');
            $table->foreign('vpn_id')
                ->references('vpn_id')
                ->on('customer_vpns')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['vpn_id']);
            $table->dropColumn('vpn_id');
        });

        Schema::dropIfExists('customer_vpns');
    }
};
