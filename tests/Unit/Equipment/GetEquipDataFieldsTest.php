<?php

namespace Tests\Unit\Equipment;

use App\Domains\Equipment\GetEquipDataFields;
use App\SystemDataFields;
use App\SystemTypes;
use Tests\TestCase;

class GetEquipDataFieldsTest extends TestCase
{
    public function test_get_all_fields()
    {
        $defaultData = [
            ['data_type_id' => 1, 'name' => 'IP Address',      'hidden' => false,],
            ['data_type_id' => 2, 'name' => 'Version',         'hidden' => false,],
            ['data_type_id' => 3, 'name' => 'Login Username',  'hidden' => false,],
            ['data_type_id' => 4, 'name' => 'Login Password',  'hidden' => false,],
            ['data_type_id' => 5, 'name' => 'Remote Access',   'hidden' => false,],
            ['data_type_id' => 6, 'name' => 'Subnet Mask',     'hidden' => false,],
            ['data_type_id' => 7, 'name' => 'Default Gateway', 'hidden' => false,],
            ['data_type_id' => 8, 'name' => 'Primary DNS',     'hidden' => false,],
            ['data_type_id' => 9, 'name' => 'Secondary DNS',   'hidden' => false,],
        ];

        $res = (new GetEquipDataFields)->getAllFields();
        $this->assertEquals($res->toArray(), $defaultData);
    }

    public function test_get_all_fields_with_stats()
    {
        $defaultData = [
            ['data_type_id' => 1, 'name' => 'IP Address',      'hidden' => false,],
            ['data_type_id' => 2, 'name' => 'Version',         'hidden' => false,],
            ['data_type_id' => 3, 'name' => 'Login Username',  'hidden' => false,],
            ['data_type_id' => 4, 'name' => 'Login Password',  'hidden' => false,],
            ['data_type_id' => 5, 'name' => 'Remote Access',   'hidden' => false,],
            ['data_type_id' => 6, 'name' => 'Subnet Mask',     'hidden' => false,],
            ['data_type_id' => 7, 'name' => 'Default Gateway', 'hidden' => false,],
            ['data_type_id' => 8, 'name' => 'Primary DNS',     'hidden' => false,],
            ['data_type_id' => 9, 'name' => 'Secondary DNS',   'hidden' => false,],
        ];
        $testSys = factory(SystemTypes::class)->create();
        for($i = 0; $i < 9; $i++)
        {
            $field = SystemDataFields::create([
                'sys_id' => $testSys->sys_id,
                'data_type_id' => $defaultData[$i]['data_type_id'],
                'order' => $i,
            ]);
            $defaultData[$i]['system_data_fields'] = [[
                'field_id' => $field->field_id,
                'sys_id'   => $testSys->sys_id,
                'data_type_id' => $defaultData[$i]['data_type_id'],
                'data_field_name' => $defaultData[$i]['name'],
                'system_types' => [
                    'sys_id' => $testSys->sys_id,
                    'name'   => $testSys->name,
                ]],
            ];
        }

        $res = (new GetEquipDataFields)->getAllFieldsWithStats();

        $this->assertEquals($defaultData, $res->toArray());
    }

    public function test_get_fields_for_equip()
    {
        $sys = factory(SystemTypes::class)->create();
        $fields = [];
        for($i = 0; $i < 5; $i++)
        {
            $fields[] = factory(SystemDataFields::class)->create([
                'sys_id' => $sys->sys_id,
                'order'  => $i,
            ])->toArray();
        }

        $res = (new GetEquipDataFields)->getFieldsForEquip($sys->sys_id);
        $this->assertEquals($fields, $res->toArray());
    }
}
