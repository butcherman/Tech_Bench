<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SendAuthCode extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $authCode)
    {
        //
    }

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
        Log::info('Sending two factor authentication code to '.$notifiable->full_name);

        return (new MailMessage)
            ->subject('Tech Bench Verification Code')
            ->greeting('Hello '.$notifiable->full_name)
            ->line('For security reasons, please enter the verification code to complete your two-factor authentication to sign into the Tech Bench')
            ->line('Verification Code: '.$this->authCode);
    }
}