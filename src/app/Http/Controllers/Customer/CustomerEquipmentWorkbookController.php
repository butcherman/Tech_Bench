<?php

namespace App\Http\Controllers\Customer;

use App\Exceptions\Customer\EquipmentWorkbookNotFoundException;
use App\Exceptions\Misc\FeatureDisabledException;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Services\Customer\CustomerWorkbookService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CustomerEquipmentWorkbookController extends Controller
{
    public function __construct(protected CustomerWorkbookService $svc)
    {
        throw_unless(
            config('customer.enable_workbooks'),
            FeatureDisabledException::class,
            'Customer Equipment Workbooks'
        );
    }

    /**
     * Show the internal version of the workbook
     */
    public function index(Customer $customer, CustomerEquipment $equipment): Response
    {
        if (! $equipment->has_workbook) {
            throw new EquipmentWorkbookNotFoundException;
        }

        return Inertia::render('Customer/Workbook/Index', [
            'customer' => $customer,
            'equipment' => $equipment,
            'workbook' => $equipment->EquipmentWorkbook->append('up_to_date'),
            'workbook-values' => Inertia::defer(
                fn () => $this->svc->getAllWorkbookValues($equipment->EquipmentWorkbook, true),
            ),
            'task-lists' => Inertia::defer(
                fn () => $equipment->EquipmentWorkbook->TaskLists,
            ),
        ]);
    }

    /**
     * Create a new workbook tied to the customers equipment
     */
    public function create(Customer $customer, CustomerEquipment $equipment): RedirectResponse
    {
        $this->svc->createWorkbook($equipment);

        return redirect()->route('customers.equipment.workbook.index', [
            $customer, $equipment,
        ]);
    }

    public function update(Customer $customer, CustomerEquipment $equipment): RedirectResponse
    {
        $this->svc->updateWorkbook($equipment);

        return redirect()->route('customers.equipment.workbook.index', [
            $customer, $equipment,
        ])->with('success', 'Workbook Updated');
    }
}
