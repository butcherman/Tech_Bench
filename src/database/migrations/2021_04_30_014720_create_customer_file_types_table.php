<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCustomerFileTypesTable extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('customer_file_types', function (Blueprint $table) {
            $table->id('file_type_id');
            $table->text('description');
            $table->timestamps();
        });

        /**
         * Default Data
         */
        $defaultData = [
            [
                'file_type_id' => 1,
                'description' => 'Equipment Backup',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'file_type_id' => 2,
                'description' => 'License',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'file_type_id' => 3,
                'description' => 'Site Map',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'file_type_id' => 4,
                'description' => 'Other',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
        ];

        DB::table('customer_file_types')->insert($defaultData);
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_file_types');
    }
}
