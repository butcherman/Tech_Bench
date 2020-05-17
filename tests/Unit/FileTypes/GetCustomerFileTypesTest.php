<?php

namespace Tests\Unit\FileTypes;

use App\Domains\FileTypes\GetCustomerFileTypes;
use Tests\TestCase;

class GetCustomerFileTypesTest extends TestCase
{


    protected $defaultData;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $this->defaultData = [
            ['file_type_id' => 1, 'description' => 'Equipment Backup'],
            ['file_type_id' => 2, 'description' => 'Installation Packet'],
            ['file_type_id' => 3, 'description' => 'License'],
            ['file_type_id' => 4, 'description' => 'Site Map'],
            ['file_type_id' => 5, 'description' => 'Other'],
        ];
    }

    public function test_execute()
    {
        $obj = new GetCustomerFileTypes;
        $types = $obj->execute();

        $this->assertEquals($types->toArray(), $this->defaultData);
    }

    public function test_execute_collection()
    {
        $defCollection = [];
        foreach($this->defaultData as $def)
        {
            $defCollection[] = [
                'value'  => $def['file_type_id'],
                'text'   => $def['description'],
            ];
        }
        $obj = new GetCustomerFileTypes;
        $types = $obj->execute(true);

        $this->assertEquals($types->toJson(), collect($defCollection)->toJson());
    }
}
