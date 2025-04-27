<?php

namespace App\Http\Controllers\Customer;

use App\Facades\CacheData;
use App\Facades\UserPermissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerEquipmentRequest;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Services\Customer\CustomerEquipmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerEquipmentController extends Controller
{
    public function __construct(protected CustomerEquipmentService $svc) {}

    /**
     *
     */
    public function index(Request $request, Customer $customer): Response
    {
        return Inertia::render('Customer/Equipment/Index', [
            'alerts' => fn() => $customer->Alerts,
            'availableEquipment' => fn() => CacheData::equipmentCategorySelectBox(),
            'customer' => fn() => $customer,
            'permissions' => fn() => UserPermissions::customerPermissions($request->user()),
            'siteList' => fn() => $customer->Sites->makeVisible(['href']),

            /**
             * Deferred Props
             */
            'equipmentList' => Inertia::defer(
                fn() => $customer->CustomerEquipment
                    ->load('CustomerSite')
                    ->groupBy('equip_name')
                    ->chunk(25)
            ),
        ]);
    }

    /**
     * Save a new piece of equipment to a customer.
     */
    public function store(CustomerEquipmentRequest $request, Customer $customer): RedirectResponse
    {
        $equip = $this->svc
            ->createEquipment($request->safe()->collect(), $customer);

        return back()->with('success', __('cust.equipment.created', [
            'equip' => $equip->equip_name,
        ]));
    }

    /**
     *
     */
    public function show(Customer $customer, CustomerEquipment $equipment): Response
    {
        //
        // return 'show';
        return Inertia::render('Customer/Equipment/Show');
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
