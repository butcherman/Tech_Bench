<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class EmailChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $newEmail;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $newEmail)
    {
        $this->newEmail = $newEmail;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     * @codeCoverageIgnore
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Email Address Changed')
            ->line('This is a notification to let you know that the email address assigned to your account has been changed to '.$this->newEmail.'.')
            ->line('If this was not done by you, please contact your System Administrator to regain access to your account')
            ->line('If this was you, you can safely ignore this email');
    }
}
