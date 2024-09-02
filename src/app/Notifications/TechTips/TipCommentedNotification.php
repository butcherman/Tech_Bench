<?php

namespace App\Notifications\TechTips;

use App\Models\TechTipComment;
use App\Models\User;
use App\Models\UserSettingType;
use App\Traits\NotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TipCommentedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use NotificationTrait;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected TechTipComment $comment)
    {
        Log::debug('Sending Tech Tip Comment Notification');
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Someone Commented On A Tech Tip')
            ->greeting('Hello ' . $notifiable->full_name)
            ->line(
                $this->comment->User->full_name .
                ' has commented on Tech Tip - .' .
                $this->comment->TechTip->subject
            )
            ->line('The comment is: ')
            ->line($this->comment->comment)
            ->action(
                'Click Here to review the comment',
                url(route('tech-tips.show', $this->comment->TechTip->slug))
            );
    }

    public function toBroadcast(): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'New Tech Tip Comment',
            'message' => 'Someone has commented on a Tech Tip',
            'href' => route('tech-tips.show', $this->comment->tip_id),
        ]);
    }
}
