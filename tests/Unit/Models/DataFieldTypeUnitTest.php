<?php

namespace Tests\Unit\Models;

use App\Models\DataFieldType;
use Tests\TestCase;

class DataFieldTypeUnitTest extends TestCase
{
    /**
     * Test Additional Attributes
     */
    public function test_additional_attributes()
    {
        $fieldType = DataFieldType::factory()->create();

        $this->assertArrayHasKey('in_use', $fieldType->toArray());
    }
}
