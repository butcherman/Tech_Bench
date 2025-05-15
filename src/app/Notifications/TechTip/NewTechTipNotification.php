<?php

namespace App\Notifications\TechTip;

use App\Models\TechTip;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewTechTipNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public TechTip $techTip) {}

    /**
     * Get the notification's delivery channels.
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
        return (new MailMessage)->markdown('mail.tips.newTip', [
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

    public function toBroadcast(object $notifiable)
    {
        // TODO - Broadcast Notification
    }
}
