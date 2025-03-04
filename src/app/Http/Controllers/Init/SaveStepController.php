<?php

namespace App\Http\Controllers\Init;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Config\BasicSettingsRequest;
use App\Http\Requests\Admin\Config\EmailSettingsRequest;
use App\Http\Requests\Admin\User\PasswordPolicyRequest;
use App\Http\Requests\Admin\User\UserAdministrationRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaveStepController extends Controller
{
    use PasswordValidationRules;

    /**
     * Save the current Init step in the session and move onto the next step.
     */
    public function __invoke(Request $request, ?User $user = null): RedirectResponse
    {
        $path = explode('/', $request->path());
        $saveData = $request->all();

        $validator = match (end($path)) {
            'basic-settings' => new BasicSettingsRequest($saveData),
            'email-settings' => new EmailSettingsRequest($saveData),
            'user-settings' => new PasswordPolicyRequest($saveData),

            default => null,
        };

        // Validate the current request.  If it is the Admin password, we build a special request
        if ($validator) {
            $validator->validate($validator->rules());
        } else {
            // Validate the Administrator Account
            if (end($path) === 'admin') {
                Validator::make($saveData, [
                    'username' => [
                        'required',
                    ],
                    'first_name' => ['required', 'string'],
                    'last_name' => ['required', 'string'],
                    'email' => [
                        'required',
                        'email',
                    ],
                    'role_id' => ['required', 'exists:user_roles'],
                ])->validateWithBag('adminUser');
            } else {
                // Validate the Admin Password
                Validator::make($saveData, [
                    'current_password' => [
                        'required',
                        'string',
                        'current_password:web',
                    ],
                    'password' => $this->tmpPasswordRules(
                        $request->session()->get(
                            'setup.user-settings'
                        )
                    ),
                ], [
                    'current_password.current_password' => __('The provided password does not match your current password.'),
                ])->validateWithBag('updatePassword');
            }
        }

        $request->session()->put('setup.' . end($path), $saveData);

        return back();
    }
}
