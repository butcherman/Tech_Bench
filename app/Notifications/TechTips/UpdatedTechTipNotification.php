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

    protected $techTip;

    /**
     * Create a new notification instance
     */
    public function __construct(TechTip $techTip)
    {
        $this->techTip = $techTip;
    }

    /**
     * Get the notification's delivery channels
     */
    public function via($notifiable)
    {
        $settingId = UserSettingType::where('name', 'Receive Email Notifications')->first()->setting_type_id;
        $allow     = $notifiable->UserSetting->where('setting_type_id', $settingId)->first()->value;

        if($allow)
        {
            return ['mail', 'database'];
        }

        return ['database'];
    }

    /**
     * Get the mail representation of the notification
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Updated Tech Tip')
                    ->greeting('Hello '.$notifiable->full_name)
                    ->line('Subject:  '.$this->techTip->subject)
                    ->line('This Tech Tip has been updated with new information')
                    ->action('Click to View the Tech Tip', url(route('tech-tips.show', $this->techTip->slug)));
    }

    /**
     * Get the array representation of the notification
     */
    public function toArray($notifiable)
    {
        return [
            'subject' => 'Updated Tech Tip',
            'data'    => [
                'tip_data' => $this->techTip,
            ]
        ];
    }
}
