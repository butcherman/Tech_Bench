<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerDeletedItemsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Customer $customer)
    {
        return Inertia::render('Customer/DeletedItems');
    }
}
