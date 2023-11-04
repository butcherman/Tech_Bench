<?php

namespace Tests\Unit\Actions;

use App\Actions\CleanPhoneNumber;
use Tests\TestCase;

class CleanPhoneNumberUnitTest extends TestCase
{
    /**
     * Process function
     */
    public function test_process_only_numbers()
    {
        $testNum = '5105551212';
        $processed = (new CleanPhoneNumber)->process($testNum);

        $this->assertEquals($testNum, $processed);
    }

    public function test_process_formatted_number()
    {
        $testNum = '(530) 587-9965';
        $processed = (new CleanPhoneNumber)->process($testNum);

        $this->assertEquals($processed, '5305879965');
    }
}
