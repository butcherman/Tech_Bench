<?php

namespace App\Notifications\TechTips;

use App\Models\TechTipComment;
use App\Models\UserSettingType;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TipCommentedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected TechTipComment $comment)
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
     *
     * TODO - Test message sent and ignore this
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Someone Commented On A Tech Tip')
            ->greeting('Hello '.$notifiable->full_name)
            ->line(
                $this->comment->User->full_name.
                ' has commented on Tech Tip - .'.
                $this->comment->TechTip->subject
            )
            ->line('The comment is: ')
            ->line($this->comment->comment)
            ->action(
                'Click Here to review the comment',
                url(route('tech-tips.show', $this->comment->TechTip->slug))
            );
    }

    /**
     * Get the array representation of the notification
     * TODO - Configure and verify DB Notification
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subject' => 'A New Tech Tip Comment Has Been Created',
            'component' => 'TechTips/TipNotification',
            'props' => [
                'tip-data' => $this->comment->TechTip->toArray(),
            ],
        ];
    }
}
