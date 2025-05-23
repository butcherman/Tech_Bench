<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\Fortify\ResetUserPassword;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ResetUserPasswordController extends Controller
{
    /**
     * Reset a users password
     */
    public function __invoke(Request $request, ResetUserPassword $svc, User $user)
    {
        $this->authorize('update', $user);

        $svc->reset($user, $request->toArray());

        return back()->with('success', 'Password Updated');
    }
}
