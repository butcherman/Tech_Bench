<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Events\UserPasswordChanged;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    /**
     * Page for a user to change their own password
     */
    public function index()
    {
        return Inertia::render('User/ChangePassword');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a reset password for the logged in user
     */
    public function store(ChangePasswordRequest $request)
    {
        $user    = User::find($request->user()->user_id);
        $expires = config('auth.passwords.settings.expire') ? Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;

        $user->forceFill(['password' => Hash::make($request->password), 'password_expires' => $expires]);
        $user->save();

        event(new UserPasswordChanged($user));
        return redirect(route('dashboard'))->with([
            'message' => 'Password successfully updated',
            'type'    => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     //
    // }
}
