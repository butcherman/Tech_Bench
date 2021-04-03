<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DisabledUserController extends Controller
{
    /**
     *  Show all disabled users
     */
    public function index()
    {
        $this->authorize('restore', User::class);

        return Inertia::render('User/disabled', [
            'user_list' => User::onlyTrashed()->get()->makeVisible(['user_id', 'deleted_at'])->makeHidden(['first_name', 'last_name', 'initials']),
        ]);
    }

    /**
     *  Restore a user who has been deactivated
     */
    public function update(Request $request, $id)
    {
        $this->authorize('restore', User::class);

        $user = User::withTrashed()->where('user_id', $id)->first();
        $user->restore();

        Log::stack(['auth', 'user'])->notice('User '.$user->username.' has been reactivated');
        return back()->with(['message' => 'User '.$user->full_name.' has been successfully reactivated', 'type' => 'success']);
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
