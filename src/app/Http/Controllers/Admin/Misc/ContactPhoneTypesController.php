<?php

namespace App\Http\Controllers\Admin\Misc;

use App\Facades\CacheFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Misc\ContactPhoneTypesRequest;
use App\Models\Customer;
use App\Models\PhoneNumberType;
use App\Services\Misc\PhoneTypeService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactPhoneTypesController extends Controller
{
    public function __construct(protected PhoneTypeService $svc) {}

    /**
     * Show a list of available phone types to Administrators
     */
    public function index(): Response
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Admin/PhoneType/Index', [
            'phone-types' => CacheFacade::phoneTypes()
                ->makeVisible(['phone_type_id']),
        ]);
    }

    /**
     * Store the newly created Phone Type.
     */
    public function store(ContactPhoneTypesRequest $request): RedirectResponse
    {
        $this->svc->createPhoneType($request->safe()->collect());

        return back()->with('success', __('admin.phone-type.created'));
    }

    /**
     * Update the Phone Type.
     */
    public function update(
        ContactPhoneTypesRequest $request,
        PhoneNumberType $phone_type
    ): RedirectResponse {
        $this->svc->updatePhoneType($request->safe()->collect(), $phone_type);

        return back()->with('success', __('admin.phone-type.updated'));
    }

    /**
     * Remove the Phone Type.
     */
    public function destroy(PhoneNumberType $phone_type): RedirectResponse
    {
        $this->authorize('manage', Customer::class);

        $this->svc->destroyPhoneType($phone_type);

        return back()->with('warning', __('admin.phone-type.destroyed'));
    }
}
