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

class UpdatedTechTipNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use NotificationTrait;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected TechTip $techTip)
    {
        //
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
            ->subject('Updated Tech Tip')
            ->greeting('Hello ' . $notifiable->full_name)
            ->line('Subject:  ' . $this->techTip->subject)
            ->line('This Tech Tip has been updated with new information')
            ->action('Click to View the Tech Tip', url(route('tech-tips.show', $this->techTip->slug)));
    }

    public function toBroadcast(): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Updated Tech Tip',
            'message' => $this->techTip->subject,
            'href' => route('tech-tips.show', $this->techTip->slug),
        ]);
    }
}
