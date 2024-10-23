<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\PhoneTypesRequest;
use App\Models\Customer;
use App\Models\PhoneNumberType;
use App\Service\Cache;
use App\Service\Misc\PhoneTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PhoneTypesController extends Controller
{
    public function __construct(protected PhoneTypeService $svc) {}

    /**
     * Show a list of available phone types to Administrators
     */
    public function index(): Response
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Admin/PhoneType/Index', [
            'phone-types' => Cache::phoneTypes()->makeVisible(['phone_type_id']),
        ]);
    }

    /**
     * Return a list of phone types from Cache
     */
    public function create(): JsonResponse
    {
        return response()->json(Cache::phoneTypes());
    }

    /**
     * Store the newly created Phone Type.
     */
    public function store(PhoneTypesRequest $request): RedirectResponse
    {
        $this->svc->createPhoneType($request->collect());

        return back()->with('success', __('admin.phone-type.created'));
    }

    /**
     * Update the Phone Type.
     */
    public function update(
        PhoneTypesRequest $request,
        PhoneNumberType $phone_type
    ): RedirectResponse {
        $this->svc->updatePhoneType($request->collect(), $phone_type);

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
