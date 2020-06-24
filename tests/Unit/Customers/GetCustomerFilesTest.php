<?php

namespace Tests\Unit\Customers;

use App\CustomerFiles;
use App\Customers;
use App\Domains\Customers\getCustomerFiles;
use Tests\TestCase;

class GetCustomerFilesTest extends TestCase
{
    public function test_execute()
    {
        $cust  = factory(Customers::class)->create();
        $files = factory(CustomerFiles::class, 5)->create(['cust_id' => $cust->cust_id]);

        $res = (new getCustomerFiles)->execute($cust->cust_id);
        $this->assertEquals($files->toArray(), $res->makeHidden(['shared', 'CustomerFileTypes', 'Files', 'User'])->toArray());
    }

    public function test_execute_with_parent()
    {
        $parent = factory(Customers::class)->create();
        $cust   = factory(Customers::class)->create(['parent_id' => $parent->cust_id]);
        $files  = factory(CustomerFiles::class, 5)->create(['cust_id' => $parent->cust_id, 'shared' => 1]);

        $res = (new getCustomerFiles)->execute($cust->cust_id);
        $this->assertEquals($files->toArray(), $res->makeHidden(['CustomerFileTypes', 'Files', 'User'])->toArray());
    }
}
