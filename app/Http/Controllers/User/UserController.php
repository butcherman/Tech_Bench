<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Models\UserEmailNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     *  User Settings Page
     */
    public function index()
    {
        $this->authorize('view', Auth::user());

        return Inertia::render('User/settings', [
            'user'   => Auth::user()->makeVisible('user_id'),
            'notify' => UserEmailNotifications::find(Auth::user()->user_id),
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
     *  Update the users account settings
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->toArray());

        return back()->with(['message' => 'Account Settings Updated', 'type' => 'success']);
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
