<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendTestEmail extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Hello '.$notifiable->full_name)
            ->line('This is a test email from the Tech Bench');
    }
}
