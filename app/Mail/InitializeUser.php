<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InitializeUser extends Mailable
{
    use Queueable, SerializesModels;
    
    public $link;
    public $username;
    public $fullName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link, $username, $name)
    {
        $this->link     = $link;
        $this->username = $username;
        $this->fullName = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcom to the Tech Bench')->markdown('email.initializeUser');
    }
}
