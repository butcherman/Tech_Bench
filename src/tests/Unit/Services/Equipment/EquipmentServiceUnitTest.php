<?php

namespace Tests\Unit\Services\Equipment;

use App\Exceptions\Database\RecordInUseException;
use App\Jobs\Customer\UpdateCustomerDataFieldsJob;
use App\Models\CustomerEquipment;
use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Services\Equipment\EquipmentService;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class EquipmentServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createCategory()
    |---------------------------------------------------------------------------
    */
    public function test_create_category(): void
    {
        $newCat = EquipmentCategory::factory()->make();

        $testObj = new EquipmentService;
        $data = $testObj->createCategory(collect($newCat->toArray()));

        $this->assertEquals(
            $newCat->toArray(),
            $data->makeHidden('cat_id')->toArray()
        );

        $this->assertDatabaseHas('equipment_categories', $newCat->toArray());
    }

    /*
    |---------------------------------------------------------------------------
    | updateCategory()
    |---------------------------------------------------------------------------
    */
    public function test_update_category(): void
    {
        $cat = EquipmentCategory::factory()->create();
        $changed = EquipmentCategory::factory()->make();

        $testObj = new EquipmentService;
        $data = $testObj->updateCategory(collect($changed->toArray()), $cat);

        $this->assertEquals($changed->only(['name']), $data->only(['name']));

        $this->assertDatabaseHas('equipment_categories', [
            'cat_id' => $cat->cat_id,
            'name' => $changed->name,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyCategory()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_category(): void
    {
        $cat = EquipmentCategory::factory()->create();

        $testObj = new EquipmentService;
        $testObj->destroyCategory($cat);

        $this->assertDatabaseMissing('equipment_categories', $cat->toArray());
    }

    public function test_destroy_category_in_use(): void
    {
        $cat = EquipmentCategory::factory()->create();
        EquipmentType::factory()->create(['cat_id' => $cat->cat_id]);

        $this->expectException(RecordInUseException::class);

        $testObj = new EquipmentService;
        $testObj->destroyCategory($cat);

        $this->assertDatabaseHas('equipment_categories', $cat->toArray());
    }

    /*
    |---------------------------------------------------------------------------
    | createEquipmentType()
    |---------------------------------------------------------------------------
    */
    public function test_create_equipment_type(): void
    {
        $category = EquipmentCategory::factory()->create();
        $equip = EquipmentType::factory()->make();
        $data = [
            'cat_id' => $category->cat_id,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'Gateway',
                'Your Mom',
            ],
        ];

        $testObj = new EquipmentService;
        $res = $testObj->createEquipmentType(collect($data));

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->cat_id, $data['cat_id']);

        $this->assertDatabaseHas('equipment_types', [
            'cat_id' => $data['cat_id'],
            'name' => $data['name'],
        ]);

        $this->assertDatabaseHas(
            'data_field_types',
            ['name' => $data['custData'][0]]
        );
        $this->assertDatabaseHas(
            'data_field_types',
            ['name' => $data['custData'][1]]
        );
        $this->assertDatabaseHas(
            'data_field_types',
            ['name' => $data['custData'][2]]
        );
    }

    /*
    |---------------------------------------------------------------------------
    | updateEquipmentType()
    |---------------------------------------------------------------------------
    */
    public function test_update_equipment_type(): void
    {
        Bus::fake();

        $existing = EquipmentType::factory()->create();
        $equip = EquipmentType::factory()->make();

        DataField::create([
            'equip_id' => $existing->equip_id,
            'type_id' => 1,
            'order' => 0,
        ]);

        $data = [
            'cat_id' => $existing->cat_id,
            'name' => $equip->name,
            'custData' => [
                'IP Address',
                'New Field',
                'Gateway',
            ],
        ];

        $testObj = new EquipmentService;
        $res = $testObj->updateEquipmentType(collect($data), $existing);

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->cat_id, $data['cat_id']);

        $this->assertDatabaseHas('equipment_types', [
            'equip_id' => $existing->equip_id,
            'cat_id' => $existing->cat_id,
            'name' => $data['name'],
        ]);

        $this->assertDatabaseHas(
            'data_field_types',
            ['name' => $data['custData'][0]]
        );
        $this->assertDatabaseHas(
            'data_field_types',
            ['name' => $data['custData'][1]]
        );
        $this->assertDatabaseHas(
            'data_field_types',
            ['name' => $data['custData'][2]]
        );

        // Verify order is correct
        $fields = DataFieldType::whereIn('name', $data['custData'])->get();
        $equip = EquipmentType::where('name', $equip->name)->first();

        $index = 0;
        foreach ($fields as $field) {
            $fieldData = DataField::where('equip_id', $equip->equip_id)
                ->where('type_id', $field->type_id)->first();

            $this->assertTrue($fieldData->order === $index);
            $index++;
        }

        Bus::assertDispatched(UpdateCustomerDataFieldsJob::class);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyEquipmentType()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_equipment_type(): void
    {
        $equip = EquipmentType::factory()->create();

        $testObj = new EquipmentService;
        $testObj->destroyEquipmentType($equip);

        $this->assertDatabaseMissing('equipment_types', $equip->makeHidden(['has_workbook'])->toArray());
    }

    public function test_destroy_equipment_type_in_use(): void
    {
        $equip = EquipmentType::factory()->create();
        CustomerEquipment::factory()->create(['equip_id' => $equip->equip_id]);

        $this->expectException(RecordInUseException::class);

        $testObj = new EquipmentService;
        $testObj->destroyEquipmentType($equip);

        $this->assertDatabaseHas('equipment_types', $equip->makeHidden(['has_workbook'])->toArray());
    }
}
