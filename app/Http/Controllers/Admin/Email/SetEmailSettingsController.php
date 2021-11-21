<?php

namespace App\Http\Controllers\Admin\Email;

use App\Events\Admin\EmailSettingsUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailSettingsRequest;
use App\Traits\AppSettingsTrait;
use Illuminate\Http\Request;

class SetEmailSettingsController extends Controller
{
    use AppSettingsTrait;

    /**
     * Save the new email settings
     */
    public function __invoke(EmailSettingsRequest $request)
    {
        //  Modify the settings so that they are entered into the database properly
        $mailSettings = [
            'mail.from.address'              => $request->from_address,
            'mail.mailers.smtp.username'     => $request->authentication ? $request->username : null,
            'mail.mailers.smtp.host'         => $request->host,
            'mail.mailers.smtp.port'         => $request->port,
            'mail.mailers.smtp.encryption'   => $request->encryption
        ];

        //  Only update the password if it has been modified
        if($request->password !== 'RandomString')
        {
            $mailSettings['mail.mailers.smtp.password'] = $request->password;
        }

        //  Submit the settings
        $this->saveArray($mailSettings);

        event(new EmailSettingsUpdatedEvent($request));
        return back()->with([
            'message' => 'Email Settings Updated',
            'type'    => 'success',
        ]);
    }
}
