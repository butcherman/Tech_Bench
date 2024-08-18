<?php

namespace App\Notifications\TechTips;

use App\Models\TechTipComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentFlaggedNotification extends Notification implements ShouldQueue
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * TODO - Assert Message sent and ignore this
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('A Tech Tip Comment has been flagged')
            ->greeting('Hello '.$notifiable->full_name)
            ->line('A comment on a Tech Tip has been flagged as inappropriate.')
            ->line('The comment is: ')
            ->line($this->comment->comment)
            ->action(
                'Click Here to review the comment and take the appropriate action',
                url(route('tech-tips.comments.index', $this->comment->TechTip->slug))
            );
    }
}
