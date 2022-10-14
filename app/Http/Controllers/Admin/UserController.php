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
use App\Models\UserSetting;
use App\Models\UserSettingType;
use App\Events\Admin\NewUserCreated;
use App\Events\Admin\UserUpdatedEvent;
use App\Events\Admin\UserDeactivatedEvent;

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
            'roles' => (new GetUserRoles)->run(/** @scrutinizer ignore-type */ Auth::user()),
        ]);
    }

    /**
     * Store a newly created user
     */
    public function store(UserRequest $request)
    {
        $newUser = User::create($request->toArray());

        //  Add the users settings data
        $settings = UserSettingType::all();
        foreach($settings as $setting)
        {
            UserSetting::create([
                'user_id'         => $newUser->user_id,
                'setting_type_id' => $setting->setting_type_id,
                'value'           => true,
            ]);
        }

        event(new NewUserCreated($newUser));
        return back()->with([
            'message' => 'New User Created',
            'type'    => 'success',
        ]);
    }

    /**
     * Show form for editing an existing user
     */
    public function edit($id)
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/User/Edit', [
            'user'  => User::where('username', $id)->firstOrFail()->makeVisible(['user_id', 'role_id']),
            'roles' => UserRoles::all(),
        ]);
    }

    /**
     * Update a user's information
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if(Auth::user()->role_id > $user->role_id)
        {
            return back()->with([
                'message' => 'You cannot modify a user with higher permissions than you',
                'type'    => 'danger'
            ]);
        }

        $user->update($request->toArray());

        // event(new UserUpdatedEvent($user));
        Log::channel('user')->notice('User '.$event->user->username.' has been updated by '.Auth::user()->username.'.  Details - ', $event->user->toArray());
        return redirect(route('admin.user.index'))->with('success', __('admin.user.updated'));
    }

    /**
     * Deactivate a user
     */
    public function destroy($id)
    {
        $user = User::where('username', $id)->firstOrFail();
        $this->authorize('create', $user);
        $user->delete();

        if($user->role_id < Auth::user()->role_id)
        {
            abort(403, 'You cannot deactivate someone with higher permissions than yourself');
        }

        event(new UserDeactivatedEvent($user));
        return back()->with([
            'message' => 'User has been deactivated',
            'type'    => 'danger',
        ]);
    }
}
