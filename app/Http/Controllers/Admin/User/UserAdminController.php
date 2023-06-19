<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\GetAvailableUserRoles;
use App\Events\User\UserCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRequest;
use App\Models\User;
use App\Models\UserLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('manage', User::class);

        return Inertia::render('Admin/User/Index', [
            'user-list' => User::with('UserRole')->get(),
        ]);
    }

    /**
     * Show the form for creating a new user
     */
    public function create(Request $request)
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/User/Create', [
            'roles' => (new GetAvailableUserRoles)->build($request->user()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $newUser = User::create($request->toArray());

        event(new UserCreatedEvent($newUser));
        Log::stack(['daily', 'user'])->notice('New User Created By '.$request->user()->username, $newUser->toArray());

        return redirect(route('admin.users.index'))->with('success', __('admin.user.created', ['user' => $newUser->full_name]));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('update', $user);

        return Inertia::render('Admin/User/Show', [
            'user' => $user->makeVisible(['created_at', 'updated_at', 'deleted_at']),
            'role' => $user->UserRole,
            'last-login' => UserLogins::where('user_id', $user->user_id)->latest('created_at')->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $user)
    {
        $this->authorize('update', $user);

        return Inertia::render('Admin/User/Edit', [
            'user' => $user->makeVisible('role_id'),
            'roles' => (new GetAvailableUserRoles)->build($request->user()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->toArray());

        Log::stack(['daily', 'user'])->info('User information for '.$user->username.' has been updated by '.$request->user()->username, $request->toArray());

        return redirect(route('admin.users.show', $user->username))->with('success', __('admin.user.updated', ['user' => $user->full_name]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('manage', $user);

        $user->delete();
        Log::stack(['daily', 'user'])->notice('User '.$user->username.' has been deactivated by '.$request->user()->username);

        return back()->with('warning', __('admin.user.disabled', ['user' => $user->full_name]));
    }

    /**
     * Restore the users profile allowing them to login again
     */
    public function restore(Request $request, User $user)
    {
        $this->authorize('manage', $user);

        $user->restore();
        Log::stack(['daily', 'user'])->notice('User '.$user->username.' has been reactivated by '.$request->user()->username);

        return back()->with('success', __('admin.user.restored', ['user' => $user->full_name]));
    }
}
