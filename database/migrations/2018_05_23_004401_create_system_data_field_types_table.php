<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SystemDataFieldTypes;

class CreateSystemDataFieldTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_data_field_types', function(Blueprint $table) {
            $table->increments('data_type_id');
            $table->string('name');
            $table->boolean('hidden')->default(0);
            $table->timestamps();
        });

        //  Enter default data
        $defaultData = [
            ['id' => 1, 'name' => 'IP Address'],
            ['id' => 2, 'name' => 'Version'],
            ['id' => 3, 'name' => 'Login Username'],
            ['id' => 4, 'name' => 'Login Password'],
            ['id' => 5, 'name' => 'Remote Access'],
            ['id' => 6, 'name' => 'Subnet Mask'],
            ['id' => 7, 'name' => 'Default Gateway'],
            ['id' => 8, 'name' => 'Primary DNS'],
            ['id' => 9, 'name' => 'Secondary DNS'],
        ];
        foreach($defaultData as $data)
        {
            DB::insert(
                'INSERT INTO `system_data_field_types` (`data_type_id`, `name`, `hidden`, `created_at`, `updated_at`)
                        VALUES (?, ?, ?, ?, ?)',
                [$data['id'], $data['name'], 0, NOW(), NOW()]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_cust_data_types');
    }
}
