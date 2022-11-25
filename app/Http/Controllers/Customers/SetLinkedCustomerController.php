<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\LinkedCustomerRequest;
use Illuminate\Http\Request;

class SetLinkedCustomerController extends Controller
{
    /**
     * Link or unlink a customer to another customer site
     */
    public function __invoke(LinkedCustomerRequest $request)
    {
        $msg = $request->processLink();

        return back()->with($msg['type'], $msg['message']);
    }
}
