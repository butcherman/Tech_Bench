<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerWorkbookRequest;
use App\Http\Requests\Customer\PublishWorkbookRequest;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerWorkbook;
use App\Services\Customer\CustomerWorkbookService;
use Illuminate\Http\JsonResponse;
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
     * Show the workbook
     */
    public function index(Customer $customer, CustomerEquipment $equipment): Response
    {
        $workbook = $this->svc->getWorkbook($customer, $equipment);

        if (is_null($workbook)) {
            abort(404, 'Workbook Not Found');
        }

        return Inertia::render('Customer/Equipment/Workbook', [
            'customer' => $customer,
            'equipment' => $equipment,
            'workbook' => $workbook,
            'values' => $this->svc->getWorkbookValues($workbook),
        ]);
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
     * Make the workbook available or unavailable via the public link
     */
    public function store(PublishWorkbookRequest $request, Customer $customer, CustomerEquipment $equipment): RedirectResponse
    {
        $workbook = $this->svc->getWorkbook($customer, $equipment);
        $this->svc->publishWorkbook($workbook, $request->safe()->collect());

        return back()->with('success', 'Workbook Updated');
    }

    /**
     * Show the public version of the workbook
     */
    public function show(CustomerWorkbook $workbook)
    {
        if (!$this->svc->validateWorkbook($workbook)) {
            return Inertia::render('Workbook/Invalid');
        }

        $this->svc->getPublicWorkbookData($workbook);

        return Inertia::render('Workbook/Show', [
            'wb-data' => $this->svc->getPublicWorkbookData($workbook),
            'wb-hash' => $workbook->wb_hash,
            'values' => $this->svc->getWorkbookValues($workbook),
        ]);


    }

    /**
     *
     */
    public function edit()
    {

    }

    /**
     * Update a field value for a workbook data entry.
     */
    public function update(CustomerWorkbookRequest $request, CustomerWorkbook $workbook): JsonResponse
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
}
