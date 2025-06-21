<?php

namespace App\Notifications\FileLink;

use App\Models\FileLink;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * @codeCoverageIgnore
 */
class LinkFileUploadedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @codeCoverageIgnore
     */
    public function __construct(public FileLink $link) {}

    /**
     * Get the notification's delivery channels.
     *
     * @codeCoverageIgnore
     */
    public function via(User $notifiable): array
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
        return (new MailMessage)->markdown('mail.fileLink.fileUploaded', [
            'link' => $this->link,
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
