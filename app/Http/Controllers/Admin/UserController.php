<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Actions\GetUserRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Models\User;
use App\Models\UserRoles;
use App\Events\Admin\UserCreatedEvent;

class UserController extends Controller
{
    /**
     * Display a listing of all active users
     */
    public function index()
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/User/Index', [
            'users' => User::with('UserRoles')->get(),
        ]);
    }

    /**
     * Show the new user form
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/User/Create', [
            'roles' => (new GetUserRoles)->run(Auth::user()),
        ]);
    }

    /**
     * Store a newly created user
     */
    public function store(UserRequest $request)
    {
        $newUser = User::create($request->toArray());

        UserCreatedEvent::dispatch($newUser);
        Log::channel('user')->notice('New User created by '.$request->user()->username.
                                     '.  Details - ', $newUser->makeVisible('user_id')->toArray());
        return redirect(route('admin.user.index'))->with('success', __('user.created'));
    }

    /**
     * Show form for editing an existing user
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return Inertia::render('Admin/User/Edit', [
            'user'  => $user->makeVisible(['user_id', 'role_id']),
            'roles' => UserRoles::all(),
        ]);
    }

    /**
     * Update a user's information
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->toArray());

        Log::channel('user')->notice('User '.$user->username.' has been updated by '.Auth::user()->username.'.  Details - ', $user->toArray());
        return redirect(route('admin.user.index'))->with('success', __('admin.user.updated'));
    }

    /**
     * Deactivate a user
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();

        Log::channel('user')->notice('User '.$user->full_name.' has been deactivated by '.Auth::user()->username);
        return back()->with('success', __('user.deactivated'));
    }
}
