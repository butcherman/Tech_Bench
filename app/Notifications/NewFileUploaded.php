<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewFileUploaded extends Notification
{
    use Queueable;
    
    protected $details;

    //  constructor receives the file link details
    public function __construct($details)
    {
        $this->details = $details;
    }

    //  Notification is sent via email and dashboard notification
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    //  Email to link owner
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new file has been uploaded to the file link - '.$this->details->link_name)
                    ->action('Click to View Link', 
                             url(route('links.info', [
                                 'id' => $this->details->link_id, 
                                 'name' => urlencode($this->details->link_name)
                            ])));
    }

    //  Dashboard notification
    public function toArray($notifiable)
    {
        return [
            //
            'type' => 'warning',
            'message' => 'New File Uploaded to link - '.$this->details->link_name,
            'link' => url(route('links.info', [
                                 'id' => $this->details->link_id, 
                                 'name' => urlencode($this->details->link_name)
                            ]))
        ];
    }
}
