<?php

namespace App\Http\Controllers\Customer;

use App\Actions\CustomerPermissions;
use App\Enum\CrudAction;
use App\Events\Customer\CustomerEquipmentEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerEquipmentRequest;
use App\Jobs\Customer\CreateCustomerDataFieldsJob;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CustomerEquipmentController extends Controller
{
    public function __construct(protected CustomerPermissions $permissions) {}

    /**
     * Home Page showing Customer Equipment and related notes/files
     */
    public function index(Request $request, Customer $customer): Response
    {
        return Inertia::render('Customer/Equipment/Index', [
            'permissions' => fn () => $this->permissions->get($request->user()),
            'customer' => fn () => $customer,
            'siteList' => fn () => $customer->CustomerSite,
            'alerts' => fn () => $customer->CustomerAlert,
            'equipmentList' => fn () => $customer->CustomerEquipment,
        ]);
    }

    /**
     * Store a newly created Customer Equipment in storage.
     */
    public function store(CustomerEquipmentRequest $request, Customer $customer): RedirectResponse
    {
        $equip = $request->createEquipment();
        dispatch(new CreateCustomerDataFieldsJob($equip));

        Log::info('New Customer Equipment added to '.$customer->name.
            ' by '.$request->user()->username, $equip->toArray());

        event(new CustomerEquipmentEvent($customer, $equip, CrudAction::Create));

        return back()->with('success', __('cust.equipment.created', [
            'equip' => $equip->equip_name,
        ]));
    }

    /**
     * Display the specified Customer Equipment.
     */
    public function show(Request $request, Customer $customer, CustomerEquipment $equipment): Response
    {
        return Inertia::render('Customer/Equipment/Show', [
            'permissions' => fn () => $this->permissions->get($request->user()),
            'customer' => fn () => $customer,
            'equipment' => fn () => $equipment,
            'siteList' => fn () => $equipment->CustomerSite->makeVisible('href'),
            'equipment-data' => fn () => $equipment->CustomerEquipmentData,
            'notes' => fn () => $equipment->CustomerNote,
            'files' => fn () => $equipment->CustomerFile->append('href'),
            'equipmentList' => fn () => [$equipment],
        ]);
    }

    /**
     * Update the specified Customer Equipment in storage.
     */
    public function update(
        CustomerEquipmentRequest $request,
        Customer $customer,
        CustomerEquipment $equipment
    ): RedirectResponse {
        $equipment->CustomerSite()->sync($request->site_list);

        Log::info('Customer Sites updated for Customer Equipment by '.
            $request->user()->username, $equipment->toArray());

        event(new CustomerEquipmentEvent($customer, $equipment, CrudAction::Update));

        return back()->with('success', __('cust.equipment.site-updated'));
    }

    /**
     * Remove the specified Customer Equipment from storage.
     */
    public function destroy(Request $request, Customer $customer, CustomerEquipment $equipment): RedirectResponse
    {
        $this->authorize('delete', $equipment);

        $equipment->delete();

        Log::notice('Customer Equipment Disabled for '.$customer->name.
            ' by '.$request->user()->username, $equipment->toArray());

        event(new CustomerEquipmentEvent($customer, $equipment, CrudAction::Destroy));

        return redirect(route('customers.equipment.index', $customer->slug))
            ->with('warning', __('cust.equipment.deleted', [
                'equip' => $equipment->equip_name,
            ]));
    }

    /**
     * Restore Soft deleted equipment
     */
    public function restore(Request $request, Customer $customer, CustomerEquipment $equipment): RedirectResponse
    {
        $this->authorize('restore', $equipment);

        $equipment->restore();
        Log::info('Customer Equipment restored for '.$customer->name.' by '.
                $request->user()->username, $equipment->toArray());

        event(new CustomerEquipmentEvent($customer, $equipment, CrudAction::Restore));

        return back()
            ->with('success', __('cust.equipment.restored', [
                'equip' => $equipment->equip_name,
            ]));
    }

    /**
     * Force delete a soft deleted equipment
     */
    public function forceDelete(Request $request, Customer $customer, CustomerEquipment $equipment): RedirectResponse
    {
        $this->authorize('forceDelete', $equipment);

        $equipment->forceDelete();
        Log::notice('Customer Equipment force deleted for '.$customer->name.
                ' by '.$request->user()->username, $equipment->toArray());

        event(new CustomerEquipmentEvent($customer, $equipment, CrudAction::ForceDelete));

        return back()
            ->with('warning', __('cust.equipment.force_deleted', [
                'equip' => $equipment->equip_name,
            ]));
    }
}
