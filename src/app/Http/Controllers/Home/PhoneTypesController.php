<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\PhoneTypesRequest;
use App\Models\Customer;
use App\Models\PhoneNumberType;
use App\Service\Cache;
use App\Service\CheckDatabaseError;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PhoneTypesController extends Controller
{
    public function index()
    {
        $this->authorize('manage', Customer::class);

        return Inertia::render('Admin/PhoneType/Index', [
            'phone-types' => Cache::phoneTypes()->makeVisible(['phone_type_id']),
        ]);
    }

    /**
     * Show the form for creating the resource.
     */
    public function create()
    {
        return Cache::phoneTypes();
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(PhoneTypesRequest $request)
    {
        $phoneType = PhoneNumberType::create($request->only(['description', 'icon_class']));
        Cache::clearCache('phoneTypes');

        Log::info(
            'New Phone Number Type created by '.$request->user()->username,
            $phoneType->toArray()
        );

        return back()->with('success', __('admin.phone-type.created'));
    }

    /**
     * Update the resource in storage.
     */
    public function update(PhoneTypesRequest $request, PhoneNumberType $phone_type)
    {
        $phone_type->update($request->only(['description', 'icon_class']));
        Cache::clearCache('phoneTypes');

        Log::info(
            'Phone Number Type updated by '.$request->user()->username,
            $phone_type->toArray()
        );

        return back()->with('success', __('admin.phone-type.updated'));
    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(Request $request, PhoneNumberType $phone_type)
    {
        $this->authorize('manage', Customer::class);

        try {

            $phone_type->delete();
            Cache::clearCache('phoneTypes');
        } catch (QueryException $e) {
            CheckDatabaseError::check($e, 'Unable to delete, Phone Number Type is in use by at least one customer');
        }

        Log::notice('Phone Number Type deleted by '.
            $request->user()->username, $phone_type->toArray());

        return back()->with('warning', __('admin.phone-type.destroyed'));
    }
}
