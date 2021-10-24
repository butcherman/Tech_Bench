<?php

namespace App\Notifications;

use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FlaggedTechTipCommentNotification extends Notification
{
    use Queueable;

    protected $comment;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TechTipComment $comment, User $user)
    {
        $this->comment = $comment;
        $this->user    = $user;
    }

    /**
     * Get the notification's delivery channels
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Hello')
                    ->line('User '.$this->user->full_name.' has flagged a comment on a Tech Tip as inappropriate.')
                    ->line('The comment is: ')
                    ->line($this->comment->comment)
                    ->action('Click Here to review the comment and take the appropriate action', url('#'));
    }

    /**
     * Get the array representation of the notification
     */
    // public function toArray($notifiable)
    // {
    //     return [
    //         //
    //     ];
    // }
}
