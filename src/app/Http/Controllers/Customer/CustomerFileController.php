<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerFileRequest;
use App\Models\Customer;
use App\Services\Customer\CustomerFileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerFileController extends Controller
{
    public function __construct(protected CustomerFileService $svc) {}

    /**
     * Store a newly created Customer File.
     */
    public function store(CustomerFileRequest $request, Customer $customer): Response
    {
        $this->svc->processFileRequest($request, $customer);

        return response()->noContent();
    }

    /**
     * Update the Customer File.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the Customer File.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Restore the Customer File.
     */
    public function restore()
    {
        //
    }

    /**
     * Force Delete the Customer File.
     */
    public function forceDelete()
    {
        //
    }
}
