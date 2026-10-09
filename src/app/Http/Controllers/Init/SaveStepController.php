<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Config\BasicSettingsRequest;
use App\Http\Requests\Admin\Config\EmailSettingsRequest;
use App\Http\Requests\Init\AdministratorAccountRequest;
use App\Http\Requests\Init\UserSecurityRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SaveStepController extends Controller
{
    /**
     * Save the current Init step in the session and move onto the next step.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $path = explode('/', $request->path());
        $saveData = $request->all();

        $validator = match (end($path)) {
            'application-settings' => new BasicSettingsRequest($saveData),
            'email-settings' => new EmailSettingsRequest($saveData),
            'security' => new UserSecurityRequest($saveData),
            'admin' => new AdministratorAccountRequest($saveData),

            default => null,
        };

        $validator->validate($validator->rules());

        $request->session()->put('setup.'.end($path), $saveData);

        return back();
    }
}
