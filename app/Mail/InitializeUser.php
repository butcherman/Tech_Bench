<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InitializeUser extends Mailable implements ShouldQueue
{
    use Queueable; // , SerializesModels;

    public $link, $username, $name;

    //  Constructor prepares the email message
    public function __construct($link, $username, $name)
    {
        $this->link     = $link;
        $this->username = $username;
        $this->name     = $name;
    }

    //  Send the email message
    public function build()
    {
        return $this->subject('Welcome to the Tech Bench')->markdown('email.initializeUser');
    }
}
