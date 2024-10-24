<?php

namespace App\Notifications\Security;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AzureCertificateExpiresSoonNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected int $daysLeft)
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
        return (new MailMessage)
            ->line('The Azure Secret for Single Sign On will expire in '.$this->daysLeft.' days.')
            ->line('To avoid interruptions in Office 365 Single Sign On, generate a new secret before the expiration date');
    }
}
