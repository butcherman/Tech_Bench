<?php

namespace App\Http\Controllers\Customer;

use App\Actions\BuildCustomerPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerEquipmentRequest;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return 'index';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return 'create';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerEquipmentRequest $request, Customer $customer)
    {
        $equip = $request->createEquipment();

        return back()->with('success', __('cust.equipment.created', ['equip' => $equip->equip_name]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Customer $customer, CustomerEquipment $equipment)
    {
        return Inertia::render('Customer/Equipment/Show', [
            'permissions' => fn() => BuildCustomerPermissions::build($request->user()),
            'customer' => fn() => $customer,
            'equipment' => fn() => $equipment,
            'siteList' => fn() => $equipment->CustomerSite,
            'equipment-data' => fn() => $equipment->CustomerEquipmentData,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerEquipmentRequest $request, Customer $customer, CustomerEquipment $equipment)
    {
        $equipment->CustomerSite()->sync($request->site_list);

        return back()->with('success', 'Sites Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }
}
