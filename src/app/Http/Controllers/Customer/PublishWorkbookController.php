<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\PublishWorkbookRequest;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Services\Customer\CustomerWorkbookService;
use Illuminate\Http\RedirectResponse;

class PublishWorkbookController extends Controller
{
    public function __construct(protected CustomerWorkbookService $svc) {}

    /**
     * Set the Publish Date on the selected workbook.
     */
    public function store(PublishWorkbookRequest $request, Customer $customer, CustomerEquipment $equipment): RedirectResponse
    {
        $this->svc->publishWorkbook(
            $equipment,
            $request->safe()->collect()->get('publish_until')
        );

        return back()->with('success', 'Workbook Published');
    }

    /**
     * Set a workbook published until date to null removing public access
     */
    public function destroy(Customer $customer, CustomerEquipment $equipment): RedirectResponse
    {
        $this->svc->unPublishWorkbook($equipment);

        return back()->with('warning', 'Workbook Unpublished');
    }
}
