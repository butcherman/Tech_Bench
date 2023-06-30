<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendTestEmail extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Test Email from Tech Bench')
            ->greeting('Hello '.$notifiable->full_name)
            ->line('This is a test message from the Tech Bench')
            ->line('If you recieved this message, your SMTP settings are working correctly');
    }
}
