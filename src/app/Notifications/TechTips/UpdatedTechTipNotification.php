<?php

namespace App\Notifications\TechTips;

use App\Models\TechTip;
use App\Models\UserSettingType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdatedTechTipNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
    public function via(object $notifiable): array
    {
        // If the user has elected to not get Email Notifications, we will only send DB Notification
        $settingsId = UserSettingType::where('name', 'Receive Email Notifications')
            ->first()
            ->setting_type_id;
        $allow = (bool) $notifiable->UserSetting
            ->where('setting_type_id', $settingsId)
            ->first()
            ->value;

        return $allow ? ['mail', 'database'] : ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Updated Tech Tip')
            ->greeting('Hello ' . $notifiable->full_name)
            ->line('Subject:  ' . $this->techTip->subject)
            ->line('This Tech Tip has been updated with new information')
            ->action('Click to View the Tech Tip', url(route('tech-tips.show', $this->techTip->slug)));
    }

    /**
     * Get the array representation of the notification
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subject' => 'A Tech Tip Has Been Updated',
            'component' => 'TechTips/TipNotification',
            'props' => [
                'tip-data' => $this->techTip->toArray(),
            ],
        ];
    }
}
