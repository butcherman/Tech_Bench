<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Admin\PasswordPolicy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordPolicyRequest;
use Illuminate\Http\Request;

class PasswordPolicyController extends Controller
{
    public function submitPasswordPolicy(PasswordPolicyRequest $request)
    {
        (new PasswordPolicy)->setPolicy($request);
        Log::notice('User '.Auth::user()->full_name.' updated User Password Policy requiring resets every '.$request->expire.' days');

        return response()->json(['success' => true]);
    }
}
