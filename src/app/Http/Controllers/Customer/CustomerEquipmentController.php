<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerEquipmentRequest;
use App\Models\Customer;
use App\Services\Customer\CustomerEquipmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerEquipmentController extends Controller
{
    public function __construct(protected CustomerEquipmentService $svc) {}

    /**
     *
     */
    public function index()
    {
        //
        return 'index';
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
    public function show(string $id)
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
