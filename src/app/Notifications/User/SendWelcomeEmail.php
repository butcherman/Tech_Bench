<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendWelcomeEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $token)
    {
        //
    }

    /**
     * Get the notification's delivery channels
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to the ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->full_name)
            ->line('A new ' . config('app.name') . ' account has been created for you.')
            ->line('Your new username is:  **' . $notifiable->username . '**')
            ->line('You can click the link below to finish setting up your account.')
            ->action('Setup Account', url(route('initialize', $this->token)));
    }
}
