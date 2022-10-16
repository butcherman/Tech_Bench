<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UpdateAccountController extends Controller
{
    /**
     * Update the Users Account Information
     */
    public function __invoke(UpdateUserRequest $request, User $user)
    {
        $user->update($request->toArray());

        Log::stack(['auth', 'user'])
            ->info('User information for User '.$user->username.' (User ID - '.$user->user_id.') has been update by '
                    .$request->user()->username);

        return back()->with('success', __('user.account_updated'));
    }
}
