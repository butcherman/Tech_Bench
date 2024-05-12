<?php

namespace App\Notifications\Security;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateExpiresSoonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected int $days)
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
     *
     * @codeCoverageIgnore
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The SSL Certificate is set to expire in '.$this->days.' days.')
            ->line('Please take action before the certificate expires!')
            ->action('Take Action', route('admin.security.index'));
    }
}
