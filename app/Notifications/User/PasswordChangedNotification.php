<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('Password Successfully Changed')
        ->line('Hello '.$notifiable->full_name)
        ->line('Your password was recently changed')
        ->line('If this was not done by you, please contact your System Administrator to regain access to your account')
        ->line('If this was you, you can safely ignore this email');
    }
}
