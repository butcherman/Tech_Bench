<?php

use App\CustomerFileTypes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerFileTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_file_types', function(Blueprint $table) {
            $table->increments('file_type_id');
            $table->text('description');
            $table->timestamps();
        });

        $defaultData = [
            ['file_type_id' => 1, 'description' => 'Equipment Backup'],
            ['file_type_id' => 2, 'description' => 'Installation Packet'],
            ['file_type_id' => 3, 'description' => 'License'],
            ['file_type_id' => 4, 'description' => 'Site Map'],
            ['file_type_id' => 5, 'description' => 'Other'],
        ];

        //  Create default data
        foreach($defaultData as $data)
        {
            CustomerFileTypes::create($data);
        }
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
