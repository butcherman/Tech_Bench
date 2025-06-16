<?php

namespace App\Notifications\TechTip;

use App\Models\TechTipComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * @codeCoverageIgnore
 */
class NewTipCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @codeCoverageIgnore
     */
    public function __construct(public TechTipComment $comment) {}

    /**
     * Get the notification's delivery channels
     *
     * @codeCoverageIgnore
     */
    public function via(object $notifiable): array
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
        return (new MailMessage)->markdown('mail.tips.newComment', [
            'user' => $notifiable,
            'comment' => $this->comment->load('TechTip'),
        ]);
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => $this->comment->TechTip->subject.' has a new Comment',
            'title' => 'New Tech Tip Comment Created',
            'href' => route('tech-tips.show', $this->comment->TechTip->slug),
        ]);
    }
}
