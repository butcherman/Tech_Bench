<?php

namespace App\Http\Controllers\Init;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Config\BasicSettingsRequest;
use App\Http\Requests\Admin\Config\EmailSettingsRequest;
use App\Http\Requests\Admin\User\PasswordPolicyRequest;
use App\Http\Requests\Admin\User\UserAdministrationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SaveSetupController extends Controller
{
    /**
     * Save the current Init step in the session and move onto the next step.
     */
    public function __invoke(Request $request)
    {
        return 'verify information';
    }
}
