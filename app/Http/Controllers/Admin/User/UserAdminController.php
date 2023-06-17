<?php

namespace App\Http\Controllers\Admin\User;

// use app\Actions\GetAvailableUserRoles;

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
            'user' => $user,
            'role' => $user->UserRole,
            'last-login' => UserLogins::where('user_id', $user->user_id)->latest('created_at')->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return Inertia::render('Admin/Users/Edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
