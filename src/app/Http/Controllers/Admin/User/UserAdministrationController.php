<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\BuildUserRoles;
use App\Events\User\UserCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserAdministrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserAdministrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/User/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/User/Create', [
            'roles' => BuildUserRoles::build($request->user()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserAdministrationRequest $request)
    {
        $newUser = User::create($request->toArray());

        event(new UserCreatedEvent($newUser));
        Log::stack(['daily', 'user'])
            ->notice('New User created by '.$request->user()->username,
                $newUser->toArray());

        return redirect(route('admin.user.show', $newUser->username))
            ->with('success', __('admin.user.created', [
                'user' => $newUser->full_name,
            ]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('Admin/User/Show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Admin/User/Edit');
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

        return redirect(route('admin.user.show', $user->username))
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
