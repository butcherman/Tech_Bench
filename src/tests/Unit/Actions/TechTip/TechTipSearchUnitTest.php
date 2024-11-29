<?php

namespace Tests\Unit\Actions\TechTip;

use App\Actions\TechTip\TechTipSearch;
use App\Models\EquipmentType;
use App\Models\TechTipEquipment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class TechTipSearchUnitTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $testData = [
            [
                'tip_id' => 1,
                'user_id' => 1,
                'tip_type_id' => 1,
                'sticky' => false,
                'public' => false,
                'subject' => $title1 = 'First Tech Tip',
                'slug' => Str::slug($title1),
                'details' => 'This is the very first test Tech Tip',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tip_id' => 2,
                'user_id' => 1,
                'tip_type_id' => 2,
                'sticky' => false,
                'public' => false,
                'subject' => $title2 = 'Second Tech Tip',
                'slug' => Str::slug($title2),
                'details' => 'Maecenas nec odio et ante',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tip_id' => 3,
                'user_id' => 1,
                'tip_type_id' => 3,
                'sticky' => false,
                'public' => false,
                'subject' => $title3 = 'Third Something Type of Randomness',
                'slug' => Str::slug($title3),
                'details' => 'Vivamus in erat ut urna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tip_id' => 4,
                'user_id' => 1,
                'tip_type_id' => 1,
                'sticky' => false,
                'public' => false,
                'subject' => $title4 = 'Fourth blah blah blah',
                'slug' => Str::slug($title4),
                'details' => 'Donec quam felis ultricies nec',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tip_id' => 5,
                'user_id' => 1,
                'tip_type_id' => 2,
                'sticky' => false,
                'public' => true,
                'subject' => $title5 = 'Fifth something or other',
                'slug' => Str::slug($title5),
                'details' => 'Wu-Tang Forever!!!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tech_tips')->insert($testData);

        $equip = EquipmentType::factory()->create();

        TechTipEquipment::create(
            ['tip_id' => 4, 'equip_id' => $equip->equip_id],
            ['tip_id' => 5, 'equip_id' => $equip->equip_id]
        );
    }

    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_no_params(): void
    {
        $data = [
            'searchFor' => null,
            'typeList' => [],
            'equipList' => [],
            'page' => 1,
            'perPage' => 25,
        ];

        $testObj = new TechTipSearch;
        $res = $testObj(collect($data), false);

        $this->assertCount(5, $res->toArray()['data']);
    }

    public function test_invoke_no_params_public(): void
    {
        $data = [
            'searchFor' => null,
            'typeList' => [],
            'equipList' => [],
            'page' => 1,
            'perPage' => 25,
        ];

        $testObj = new TechTipSearch;
        $res = $testObj(collect($data), true);

        $this->assertCount(1, $res->toArray()['data']);
    }

    public function test_invoke_type_param(): void
    {
        $data = [
            'searchFor' => null,
            'typeList' => [2],
            'equipList' => [],
            'page' => 1,
            'perPage' => 25,
        ];

        $testObj = new TechTipSearch;
        $res = $testObj(collect($data), false);

        $this->assertCount(2, $res->toArray()['data']);
    }

    public function test_invoke_type_param_public(): void
    {
        $data = [
            'searchFor' => null,
            'typeList' => [2],
            'equipList' => [],
            'page' => 1,
            'perPage' => 25,
        ];

        $testObj = new TechTipSearch;
        $res = $testObj(collect($data), true);

        $this->assertCount(1, $res->toArray()['data']);
    }
}
