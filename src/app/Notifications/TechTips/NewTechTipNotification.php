<?php

namespace App\Notifications\TechTips;

use App\Models\TechTip;
use App\Models\User;
use App\Models\UserSettingType;
use App\Traits\NotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewTechTipNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use NotificationTrait;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected TechTip $techTip)
    {
        Log::debug('Sending Tech Tip Notification');
    }

    /**
     * Get the notification's delivery channels
     */
    public function via(User $notifiable): array
    {
        return $this->getViaArray($notifiable);
    }

    /**
     * Get the mail representation of the notification.
     *
     * TODO - Test message sent and ignore this
     */
    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('A New Tech Tip Has Been Created')
            ->greeting('Hello ' . $notifiable->full_name)
            ->line('A new Tech Tip has recently been created')
            ->line('Subject:  ' . $this->techTip->subject)
            ->action(
                'Click to View the Tech Tip',
                url(route('tech-tips.show', $this->techTip->slug))
            );
    }

    public function toBroadcast(): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'New Tech Tip',
            'message' => $this->techTip->subject,
            'href' => route('tech-tips.show', $this->techTip->slug),
        ]);
    }
}
