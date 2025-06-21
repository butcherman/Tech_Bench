<?php

namespace App\Notifications\TechTip;

use App\Models\TechTip;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * @codeCoverageIgnore
 */
class NewTechTipNotification extends Notification implements ShouldQueue
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
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => $this->techTip->subject,
            'title' => 'New Tech Tip Created',
            'href' => route('tech-tips.show', $this->techTip->slug),
        ]);
    }
}
