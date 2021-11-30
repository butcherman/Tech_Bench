<?php

namespace App\Notifications;

use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTechTipCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comment;
    protected $user;
    protected $tip;

    /**
     * Create a new notification instance
     */
    public function __construct(TechTipComment $comment, User $user)
    {
        $this->comment = $comment;
        $this->user    = $user;
        $this->tip     = TechTip::find($comment->tip_id);
    }

    /**
     * Get the notification's delivery channels
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Hello')
                    ->line($this->user->full_name.' has commented on Tech Tip - .'.$this->tip->subject)
                    ->line('The comment is: ')
                    ->line($this->comment->comment)
                    ->action('Click Here to review the comment', url(route('tech-tips.show', $this->tip->slug)));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'subject' => 'New Tech Tip Comment',
            'data'    => [
                'tip'     => $this->tip,
                'comment' => $this->comment,
                'user'    => $this->user,
            ],
        ];
    }
}
