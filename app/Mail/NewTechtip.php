<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\TechTips;

class NewTechtip extends Mailable
{
    use Queueable, SerializesModels;
    
    public $tip;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TechTips $tip)
    {
        // 
        $this->tip = $tip;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.newTechTip');
    }
}
