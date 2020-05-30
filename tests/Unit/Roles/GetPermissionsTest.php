<?php

namespace Tests\Unit\Roles;

use App\Domains\Roles\GetPermissions;
use Tests\TestCase;

class GetPermissionsTest extends TestCase
{
    public function test_get_all_permissions()
    {
        $defaultData = [
            ['perm_type_id' => 1,  'description' => 'Manage Users',        ],
            ['perm_type_id' => 2,  'description' => 'Manage Permissions',  ],
            ['perm_type_id' => 3,  'description' => 'Run Reports',         ],
            ['perm_type_id' => 4,  'description' => 'Add Customer',        ],
            ['perm_type_id' => 5,  'description' => 'Manage Customers',    ],
            ['perm_type_id' => 6,  'description' => 'Deactivate Customer', ],
            ['perm_type_id' => 7,  'description' => 'Use File Links',      ],
            ['perm_type_id' => 8,  'description' => 'Create Tech Tip',     ],
            ['perm_type_id' => 9,  'description' => 'Edit Tech Tip',       ],
            ['perm_type_id' => 10, 'description' => 'Delete Tech Tip',     ],
            ['perm_type_id' => 11, 'description' => 'Manage Equipment',    ],
        ];

        $res = (new GetPermissions)->getAllPermissions();

        $this->assertEquals($defaultData, $res->toArray());
    }
}
