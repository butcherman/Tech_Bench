<?php

namespace App\Http\Controllers\Customer;

use App\Enums\DiskEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\Customer\CustomerFileRequest;
use App\Models\Customer;
use App\Services\Customer\CustomerFileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class CustomerFileController extends FileUploadController
{
    public function __construct(protected CustomerFileService $svc) {}

    /**
     * Save and store an uploaded file.
     */
    public function store(CustomerFileRequest $request, Customer $customer): Response
    {
        // Process the file upload first
        $this->setFileData(DiskEnum::customers, $customer->cust_id);
        $savedFile = $this->getChunk($request->file('file'), $request);

        if ($savedFile) {
            $this->svc->createCustomerFile(
                $request->safe()->collect(),
                $savedFile,
                $customer,
                $request->user()
            );
        }

        return response()->noContent();
    }

    /**
     *
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     *
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }

    /**
     *
     */
    public function restore(string $id)
    {
        //
        return 'restore';
    }

    /**
     *
     */
    public function forceDelete(string $id)
    {
        //
        return 'force delete';
    }
}
