<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;

use App\Models\User;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

class DisabledUserController extends Controller
{
    /**
     *  Show all disabled users
     */
    public function index()
    {
        $this->authorize('create', User::class);

        return Inertia::render('User/disabled', [
            'user_list' => User::onlyTrashed()->get()->makeVisible(['user_id', 'deleted_at'])->makeHidden(['first_name', 'last_name', 'initials']),
        ]);
    }

    /**
     *  Restore a user who has been deactivated
     */
    public function update($id)
    {
        $this->authorize('restore', User::withTrashed()->where('user_id', $id)->first());

        $user = User::withTrashed()->where('user_id', $id)->first();
        $user->restore();

        Log::stack(['auth', 'user'])->notice('User '.$user->username.' has been reactivated');
        return back()->with(['message' => 'User '.$user->full_name.' has been successfully reactivated', 'type' => 'success']);
    }

    /**
     *  Perminately delete a user and all of their information
     */
    // public function destroy($id)
    // {
    //     //
    // }
}
