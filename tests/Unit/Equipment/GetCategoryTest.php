<?php

namespace Tests\Unit\Equipment;

use App\Domains\Equipment\GetCategory;
use App\SystemCategories;
use Tests\TestCase;

class GetCategoryTest extends TestCase
{
    protected $testCat;

    public function setUp():void
    {
        Parent::setup();

        $this->testCat = factory(SystemCategories::class)->create();
    }

    public function test_get_category_data()
    {
        $data = (new GetCategory)->getCategoryData($this->testCat->cat_id);

        $this->assertEquals($data->toArray(), $this->testCat->toArray());
    }
}
