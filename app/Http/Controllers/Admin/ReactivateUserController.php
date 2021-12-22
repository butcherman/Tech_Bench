<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Events\Admin\UserReactivatedEvent;

class ReactivateUserController extends Controller
{
    /**
     * Re-enable a user who has been deactivated
     */
    public function __invoke($id)
    {
        $this->authorize('manage', User::class);

        $user = User::withTrashed()->where('username', $id)->firstOrFail();
        $user->restore();

        event(new UserReactivatedEvent($user));
        return back()->with([
            'message' => $user->full_name.' successfully re-activated',
            'type'    => 'success',
        ]);
    }
}
