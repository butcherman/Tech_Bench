<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\CustomerFileTypes;

class CreateCustomerFileTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_file_types', function (Blueprint $table) {
            $table->increments('file_type_id');
            $table->text('description');
            $table->timestamps();
        });
        
        //  Create default data
        CustomerFileTypes::create([
            'file_type_id' => 1,
            'description'  => 'Backup'
        ]);
        
        CustomerFileTypes::create([
            'file_type_id' => 2,
            'description'  => 'Handout'
        ]);
        
        CustomerFileTypes::create([
            'file_type_id' => 3,
            'description'  => 'Job Packet'
        ]);
        
        CustomerFileTypes::create([
            'file_type_id' => 4,
            'description'  => 'License'
        ]);
        
        CustomerFileTypes::create([
            'file_type_id' => 5,
            'description'  => 'Site Map'
        ]);
        
        CustomerFileTypes::create([
            'file_type_id' => 6,
            'description'  => 'Other'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_file_types');
    }
}
