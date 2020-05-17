<?php

namespace Tests\Unit\TechTips;

use Tests\TestCase;

use App\Domains\TechTips\GetTechTipTypes;

class GetTechTipTypesTest extends TestCase
{
    protected $defaultData;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $this->defaultData = [
            ['tip_type_id' => 1, 'description' => 'Tech Tip'],
            ['tip_type_id' => 2, 'description' => 'Documentation'],
            ['tip_type_id' => 3, 'description' => 'Software'],
        ];
    }

    public function test_execute()
    {
        $obj = new GetTechTipTypes;
        $types = $obj->execute();

        $this->assertEquals($types->toArray(), $this->defaultData);
    }

    public function test_execute_collection()
    {
        $defCollection = [];
        foreach($this->defaultData as $def)
        {
            $defCollection[] = [
                'text' => $def['description'],
                'value'  => $def['tip_type_id'],
            ];
        }
        $obj = new GetTechTipTypes;
        $types = $obj->execute(true);

        $this->assertEquals($types->toJson(), collect($defCollection)->toJson());
    }
}
