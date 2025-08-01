<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerWorkbookRequest;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerWorkbook;
use App\Services\Customer\CustomerWorkbookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerEquipmentWorkbookController extends Controller
{
    public function __construct(protected CustomerWorkbookService $svc)
    {
    }

    /**
     *
     */
    public function index()
    {
        //
        return 'index';
    }

    /**
     * Create a blank workbook for the customer and equipment
     */
    public function create(Customer $customer, CustomerEquipment $equipment): RedirectResponse
    {
        $this->svc->createWorkbook($customer, $equipment);

        return back()->with('success', 'Workbook Created');
    }

    /**
     *
     */
    public function store(Request $request)
    {
        //
        return 'store';
    }

    /**
     * View the Equipment Workbook
     */
    public function show(Customer $customer, CustomerEquipment $equipment): Response
    {
        $workbook = $this->svc->getWorkbook($customer, $equipment);

        if (is_null($workbook)) {
            abort(404, 'Workbook Not Found');
        }

        return Inertia::render('Customer/Equipment/Workbook', [
            'customer' => $customer,
            'equipment' => $equipment,
            'workbookHash' => $workbook->wb_hash,
            'workbookData' => $workbook->wb_data,
            'values' => $this->svc->getWorkbookValues($workbook),
        ]);
    }

    /**
     *
     */
    public function edit(string $id)
    {
        //
        return 'edit';
    }

    /**
     * Update a field value for a workbook data entry.
     */
    public function update(CustomerWorkbookRequest $request, CustomerWorkbook $workbook)
    {
        $this->svc->setWorkbookValue($workbook, $request->safe()->collect());

        return response()->json(['success' => true]);
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
