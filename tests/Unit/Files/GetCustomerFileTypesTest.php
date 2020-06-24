<?php

namespace Tests\Unit\Files;

use App\Domains\Files\GetCustomerFileTypes;
use Tests\TestCase;

class GetCustomerFileTypesTest extends TestCase
{
    public function test_execute()
    {
        $defaultData = [
            ['file_type_id' => 1, 'description' => 'Equipment Backup',   ],
            ['file_type_id' => 2, 'description' => 'Installation Packet',],
            ['file_type_id' => 3, 'description' => 'License',            ],
            ['file_type_id' => 4, 'description' => 'Site Map',           ],
            ['file_type_id' => 5, 'description' => 'Other',              ],
        ];

        $res = (new GetCustomerFileTypes)->execute();
        $this->assertEquals($defaultData, $res->toArray());
    }
}
