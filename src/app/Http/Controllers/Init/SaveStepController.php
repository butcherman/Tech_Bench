<?php

// TODO - Refactor

namespace App\Http\Controllers\Init;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BasicSettingsRequest;
use App\Http\Requests\Admin\EmailSettingsRequest;
use App\Http\Requests\Admin\PasswordPolicyRequest;
use App\Http\Requests\Admin\UserAdministrationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaveStepController extends Controller
{
    use PasswordValidationRules;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $path = explode('/', $request->path());
        $saveData = $request->all();

        $validator = match (end($path)) {
            'basic-settings' => new BasicSettingsRequest($saveData),
            'email-settings' => new EmailSettingsRequest($saveData),
            'user-settings' => new PasswordPolicyRequest($saveData),
            'admin' => new UserAdministrationRequest($saveData),
            default => null,
        };

        // Validate the current request.  If it is the Admin password, we build a special request
        if ($validator) {
            $validator->validate($validator->rules());
        } else {
            Validator::make($saveData, [
                'current_password' => ['required', 'string', 'current_password:web'],
                'password' => $this->tmpPasswordRules($request->session()->get('setup.user-settings')),
            ], [
                'current_password.current_password' => __('The provided password does not match your current password.'),
            ])->validateWithBag('updatePassword');
        }

        $request->session()->put('setup.'.end($path), $saveData);

        return back();
    }
}
