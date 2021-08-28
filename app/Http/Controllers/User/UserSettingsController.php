<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use Illuminate\Http\Request;

use App\Traits\UserSettingsTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserSettingsController extends Controller
{
    use UserSettingsTrait;

    /**
     *  Show the User Settings Page
     */
    public function index(Request $request)
    {
        return Inertia::render('User/Settings', [
            'user'     => $request->user()->makeVisible('user_id'),
            'settings' => $this->filterUserSettings($request->user()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    }

    /**
     *  update a users settings
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->toArray());

        Log::stack(['auth', 'user'])->info('User information for User '.$user->username.' (User ID - '.$user->user_id.')has been update by '.$request->user()->username);

        return back()->with([
            'message' => 'Account Details Updated',
            'type'    => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
