<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendWelcomeEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $token;

    /**
     * Create a new notification instance
     */
    public function __construct(User $user, $token)
    {
        $this->user  = $user;
        $this->token = $token;
    }

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
                    ->greeting('Hello '.$this->user->full_name)
                    ->line('A new '.config('app.name').' account has been created for you.')
                    ->line('Your new username is:  **'.$this->user->username.'**')
                    ->line('You can click the link below to finish setting up your account.')
                    ->action('Setup Account', url(route('initialize', $this->token)));
    }
}
