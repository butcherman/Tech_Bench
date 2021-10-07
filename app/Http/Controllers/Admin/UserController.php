<?php

namespace App\Http\Controllers\Admin;

use App\Actions\GetUserRoles;
use App\Events\Admin\NewUserCreated;
use App\Events\Admin\UserDeactivatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Models\User;
use App\Models\UserRoles;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        return 'working';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        return 'submitted';
    }

    /**
     * Deactivate a user
     */
    public function destroy($id)
    {
        $user = User::where('username', $id)->firstOrFail();
        $this->authorize('create', $user);
        $user->delete();

        event(new UserDeactivatedEvent($user));
        return back()->with([
            'message' => 'User has been deactivated',
            'type'    => 'danger',
        ]);
    }
}
