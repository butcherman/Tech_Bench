<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Domains\Admin\PasswordPolicy;

use App\Http\Requests\Admin\PasswordPolicyRequest;

class PasswordPolicyController extends Controller
{
    public function submitPolicy(PasswordPolicyRequest $request)
    {
        (new PasswordPolicy)->setPolicy($request);
        Log::notice('User '.Auth::user()->full_name.' updated User Password Policy requiring resets every '.$request->expire.' days');

        return response()->json(['success' => true]);
    }
}
