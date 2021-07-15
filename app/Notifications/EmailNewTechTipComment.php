<?php

namespace App\Notifications;

use App\Events\NewTipCommentEvent;
use App\Models\TechTip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailNewTechTipComment extends Notification
{
    use Queueable;

    protected $commentData;
    protected $tipData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(NewTipCommentEvent $commentData)
    {
        $this->commentData = $commentData;
        $this->tipData     = TechTip::find($commentData->tip_id);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('A Tech Tip has been commented on')
                    ->greeting($this->commentData->user.' has commented on Tech Tip - '.$this->tipData->subject)
                    ->line($this->commentData->comment)
                    ->action('Click to View the Tech Tip', url(route('tech-tips.show', $this->tipData->slug)));
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
            //
        ];
    }
}
