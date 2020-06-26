<?php

namespace App\Domains\Admin;

use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Log;

use App\Mail\TestEmail;

use Swift_Mailer;
use Swift_SmtpTransport;

class SetEmailProperties extends SettingsDomain
{
    //  Save the new Email Settings
    public function saveEmailSettings($request)
    {
        $username = $request->authentication ? $request->username : null;
        $password = $request->authentication ? $request->password : null;
        $mailSettings = [
            'mail.from.address' => $request->from_address,
            'mail.username'     => $username,
            'mail.password'     => $password,
            'mail.host'         => $request->host,
            'mail.port'         => $request->port,
            'mail.encryption'   => $request->encryption
        ];

        foreach($mailSettings as $key => $value)
        {
            $this->updateSettings($key, $value);
            if($key != 'mail.password')
            {
                Log::debug('Email Setting Key '.$key.' updated to '.$value);
            }
        }

        return true;
    }

    //  Send a test email using temporary credentials to verify if the settings are correct or not
    public function sendTestEmail($request, $emailAddress)
    {
        $password = $request->password === 'RandomString' ? config('mail.password') : $request->password;

        $transport = new Swift_SmtpTransport($request->host, $request->port);
        $transport->setUsername($request->username);
        $transport->setPassword($password);
        $transport->setEncryption($request->encryption);

        $swiftMailer = new Swift_Mailer($transport);

        $mail = new Mailer('Test Email', app()->get('view'), $swiftMailer);
        $mail->alwaysFrom($request->from_address);
        $mail->alwaysReplyTo($request->from_address);

        try
        {
            $mail->to($emailAddress)->send(new TestEmail);
            Log::info('Test email sent to '.$emailAddress);
        }
        catch(\Exception $e)
        {
            Log::alert('Test Email to '.$emailAddress.' failed.  Reason - ', array($e));
            return $e->getMessage();
        }

        return true;
    }
}
