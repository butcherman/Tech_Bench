<?php

namespace App\Notifications;

use App\TechTips;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class NewTechTipCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $name, $tipID;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $tipID)
    {
        $this->name = $name;
        $this->tipID = $tipID;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $subject = TechTips::find($this->tipID)->subject;

        return [
            'type'    => 'warning',
            'message' => $this->name.' commented on your Tech Tip',
            'link'    => url(route(
                'tips.details',
                [
                    'id' => $this->tipID,
                    'name' => urlencode($subject)
                ]
            ))
        ];
    }
}
