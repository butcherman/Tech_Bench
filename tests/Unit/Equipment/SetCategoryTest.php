<?php

namespace Tests\Unit\Equipment;

use App\Domains\Equipment\SetCategory;
use App\Http\Requests\Equipment\CategoryRequest;
use App\SystemCategories;
use App\SystemTypes;
use Tests\TestCase;

class SetCategoryTest extends TestCase
{
    protected $testCat, $testObj;

    public function setUp():void
    {
        Parent::setup();

        $this->testCat = factory(SystemCategories::class)->create();
        $this->testObj = new SetCategory;
    }

    public function test_create_category()
    {
        $data = new CategoryRequest([
            'name' => factory(SystemCategories::class)->make()->name,
        ]);

        $res = $this->testObj->createCategory($data);
        $this->assertTrue($res);
        $this->assertDatabaseHas('system_categories', ['name' => $data->name]);
    }

    public function test_update_category()
    {
        $data = new CategoryRequest([
            'name' => factory(SystemCategories::class)->make()->name,
        ]);

        $res = $this->testObj->updateCategory($data, $this->testCat->cat_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('system_categories', ['cat_id' => $this->testCat->cat_id, 'name' => $data->name]);
    }

    public function test_delete_category()
    {
        $res = $this->testObj->deleteCategory($this->testCat->cat_id);
        $this->assertTrue($res);
        $this->assertDatabaseMissing('system_categories', ['cat_id' => $this->testCat->cat_id]);
    }

    public function test_delete_category_fail()
    {
        factory(SystemTypes::class, 5)->create([
            'cat_id' => $this->testCat->cat_id,
        ]);

        $res = $this->testObj->deleteCategory($this->testCat->cat_id);
        $this->assertFalse($res);
        $this->assertDatabaseHas('system_categories', ['cat_id' => $this->testCat->cat_id]);
    }
}
