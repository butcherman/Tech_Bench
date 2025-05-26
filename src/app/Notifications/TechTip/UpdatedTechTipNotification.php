<?php

namespace App\Notifications\TechTip;

use App\Models\TechTip;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdatedTechTipNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @codeCoverageIgnore
     */
    public function __construct(public TechTip $techTip) {}

    /**
     * Get the notification's delivery channels.
     *
     * @codeCoverageIgnore
     */
    public function via(object $notifiable): array
    {
        if ($notifiable->checkUserSetting('Receive Email Notifications')) {
            return ['mail', 'broadcast'];
        }

        return ['broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->markdown('mail.tips.updatedTip', [
            'user' => $notifiable,
            'techTip' => $this->techTip,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
