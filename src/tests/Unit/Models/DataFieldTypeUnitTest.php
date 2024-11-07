<?php

namespace Tests\Unit\Models;

use App\Models\DataFieldType;
use Tests\TestCase;

class DataFieldTypeUnitTest extends TestCase
{
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = DataFieldType::factory()->create();
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function test_model_attributes(): void
    {
        // in_use should not exist on a regular model load
        $this->assertFalse(array_key_exists('in_use', $this->model->toArray()));
    }

    public function test_model_attributes_appended(): void
    {
        $this->assertArrayHasKey(
            'in_use',
            $this->model->append('in_use')->toArray()
        );
    }
}
