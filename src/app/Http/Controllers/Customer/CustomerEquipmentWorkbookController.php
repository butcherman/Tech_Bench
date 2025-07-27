<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Services\Customer\CustomerWorkbookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
    public function show(Customer $customer, CustomerEquipment $equipment)
    {
        //
        return 'show';
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
