<?php

namespace Tests\Unit\Models;

use App\Models\DataField;
use App\Models\DataFieldType;
use Tests\TestCase;

class DataFieldUnitTest extends TestCase
{
    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $type = DataFieldType::factory()->create();
        $field = DataField::factory()->create(['type_id' => $type->type_id]);

        $this->assertEquals($field->DataFieldType->only(['type_id', 'name']), $type->only(['type_id', 'name']));
    }
}
