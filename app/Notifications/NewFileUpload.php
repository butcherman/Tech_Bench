<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewFileUpload extends Notification implements ShouldQueue
{
    use Queueable;
    protected $details;

    //  Constructor receives the file link details
    public function __construct($details)
    {
        $this->details = $details;
    }

    //  Notification is sent via email and dashboard notification
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    //  Email notification
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('A new file has been uploaded to the file link - '.$this->details->link_name)
            ->action('Click to View Link',
                url(route('links.details',
                [
                        'id' => $this->details->link_id,
                        'name' => urlencode($this->details->link_name)
                ])));
    }

    //  Dashboard notification
    public function toArray($notifiable)
    {
        return [
            'type'    => 'warning',
            'message' => 'New File(s) Uploaded to link - '.$this->details->link_name,
            'link'    => url(route('links.details',
                            [
                            'id' => $this->details->link_id,
                                'name' => urlencode($this->details->link_name)
                            ]))
        ];
    }
}
