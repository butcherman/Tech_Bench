<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserAdministrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserAdministrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserAdministrationRequest $request, User $user)
    {
        $user->update($request->toArray());

        Log::stack(['daily', 'user'])
            ->info('User information for '.$user->username.' has been updated by '.
                $request->user()->username, $request->toArray());

        // This function is also used during first time setup
        if (config('app.first_time_setup')) {
            return back()->with('success', __('admin.user.updated', [
                'user' => $user->full_name,
            ]));
        }

        return redirect(route('admin.users.show', $user->username))
            ->with('success', __('admin.user.updated', [
                'user' => $user->full_name,
            ]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
