<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewTechTip extends Notification implements ShouldQueue
{
    use Queueable;
    protected $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        //
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new Tech Tip has been created')
                    ->line('Subject:  '.$this->details->subject)
                    ->action('Click to view Tech Tip', url(route('tip.details', ['id' => $this->details->tip_id, 'name' => urlencode($this->details->subject)])));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
            'type'    => 'warning',
            'message' => 'New Tech Tip Created - ' . $this->details->subject,
            'link'    => url(route(
                'tip.details',
                    [
                        'id' => $this->details->tip_id,
                        'name' => urlencode($this->details->subject)
                    ]
                ))
        ];
    }
}
