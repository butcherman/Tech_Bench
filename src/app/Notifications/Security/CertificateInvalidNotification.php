<?php

namespace App\Notifications\Security;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateInvalidNotification extends Notification implements ShouldQueue
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
            ->line('The SSL Certificate is Invalid.')
            ->line('This means that communication between the Tech Bench and users is not secure.')
            ->line('Please take action to correct this as soon as possible!')
            ->action('Take Action', route('admin.security.index'));
    }
}
