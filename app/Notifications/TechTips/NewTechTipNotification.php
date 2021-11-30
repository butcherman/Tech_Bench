<?php

namespace App\Notifications\TechTips;

use App\Models\TechTip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTechTipNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $techTip;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TechTip $techTip)
    {
        $this->techTip = $techTip;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
                    ->greeting('New Tech Tip')
                    ->line('Subject:  '.$this->techTip->subject)
                    ->action('Click to View the Tech Tip', url(route('tech-tips.show', $this->techTip->slug)));
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
            'subject' => 'New Tech Tip',
            'data'    => [
                'tip_data' => $this->techTip,
            ]
        ];
    }
}
