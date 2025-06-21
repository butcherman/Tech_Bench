<?php

namespace Tests\Unit\Actions\TechTip;

use App\Actions\TechTip\TechTipSearch;
use App\Models\EquipmentType;
use App\Models\TechTip;
use Tests\TestCase;

class TechTipSearchUnitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setup();

        $testData = [
            [
                'tip_id' => 1,
                'user_id' => 1,
                'tip_type_id' => 1,
                'sticky' => false,
                'subject' => 'First Tech Tip',
                'details' => 'This is the very first test Tech Tip',
                'public' => false,
            ],
            [
                'tip_id' => 2,
                'user_id' => 1,
                'tip_type_id' => 2,
                'sticky' => false,
                'subject' => 'Second Tech Tip',
                'details' => 'Maecenas nec odio et ante',
                'public' => false,
            ],
            [
                'tip_id' => 3,
                'user_id' => 1,
                'tip_type_id' => 3,
                'sticky' => false,
                'subject' => 'Third Something Type of Randomness',
                'details' => 'Vivamus in erat ut urna',
                'public' => true,
            ],
            [
                'tip_id' => 4,
                'user_id' => 1,
                'tip_type_id' => 1,
                'sticky' => false,
                'subject' => 'Fourth blah blah blah',
                'details' => 'Donec quam felis ultricies nec',
                'public' => true,
            ],
            [
                'tip_id' => 5,
                'user_id' => 1,
                'tip_type_id' => 2,
                'sticky' => false,
                'subject' => 'Fifth something or other',
                'details' => 'Wu-Tang Forever!!!',
                'public' => false,
            ],
        ];

        foreach ($testData as $data) {
            TechTip::factory()->create($data);
        }

        $equipment = EquipmentType::factory()->count(3)->create();

        $tips = TechTip::all();
        $index = 0;

        foreach ($tips as $tip) {
            $tip->Equipment()->sync([$equipment[$index]->equip_id]);

            if ($index === 2) {
                $index = 0;
            } else {
                $index++;
            }
        }
    }

    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_some_tips(): void
    {
        $data = [
            'searchFor' => '',
            'typeList' => [1],
            'equipList' => [1],
            'page' => 1,
            'perPage' => 25,
        ];

        $testObj = new TechTipSearch;
        $res = $testObj(collect($data), true);

        // Null Driver returns zero results
        $this->assertCount(0, $res->toArray()['data']);
    }

    public function test_invoke_all_tips(): void
    {
        $data = [
            'searchFor' => '',
            'typeList' => [],
            'equipList' => [],
            'page' => 1,
            'perPage' => 25,
        ];

        $testObj = new TechTipSearch;
        $res = $testObj(collect($data));

        $this->assertCount(5, $res->toArray()['data']);
    }

    public function test_invoke_all_public_tips(): void
    {
        $data = [
            'searchFor' => '',
            'typeList' => [],
            'equipList' => [],
            'page' => 1,
            'perPage' => 25,
        ];

        $testObj = new TechTipSearch;
        $res = $testObj(collect($data), true);

        $this->assertCount(2, $res->toArray()['data']);
    }
}
