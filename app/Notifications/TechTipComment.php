<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TechTipComment extends Notification
{
    use Queueable;

    protected $details;
    
    //  Constructor receives basic Tech Tip data
    public function __construct($details)
    {
        $this->details = $details;
    }

    //  Notifications sent via dashboard notification
    public function via($notifiable)
    {
        return ['database'];
    }

    //  Email Notification
    public function toMail($notifiable)
    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
    }

    //  Dashboard Notification
    public function toArray($notifiable)
    {
        return [
            'type'    => 'warning',
            'message' => $this->details['user'].' added a comment to your Tech Tip - '.$this->details['title'],
            'link'    => url(route('tip.details', ['id' => $this->details['tip_id'], 'name' => urlencode($this->details['title'])]))
        ];
    }
}
