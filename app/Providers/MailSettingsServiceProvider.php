<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Config;

class MailSettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
//        if(Schema::hasTable('settings_email'))
//        {
//            $mail = DB::table('settings_email')->first();
//            if($mail)
//            {
//                $config = [
//                    'driver'     => $mail->driver,
//                    'host'       => $mail->host,
//                    'port'       => $mail->port,
//                    'from'       => ['address' => $mail->from_address, 'name' => $mail->from_name],
//                    'encryption' => $mail->encryption,
//                    'username'   => $mail->username,
//                    'password'   => $mail->password,
//                    'sendmail'   => '/usr/sbin/sendmail -bs',
//                    'pretend'    => false
//                ];
//                Config('mail', $config);
//            }
//        }
        
        
        Config(['mail.driver' => 'smtp',
                'mail.host' => 'smtp.mailtrap.io',
                'mail.port' => '2525',
                
                'mail.encryption' => 'tls',
                'mail.username' => '4cc5ceb8dbedc2',
                'mail.password' => '617042ec6b20bb'
               
               
               
               ]);
        
        
        
        
        
    }
}
