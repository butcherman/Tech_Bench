<?php

namespace App\Notifications\User;

use App\Http\Requests\Admin\User\UserNotificationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subject;

    protected $message;

    protected $from;

    /**
     * Create a new notification instance.
     */
    public function __construct(UserNotificationRequest $request)
    {
        $this->subject = $request->subject;
        $this->message = $request->message;
        $this->from = $request->user()->full_name;
    }

    /**
     * Get the notification's delivery channels
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->line('You have received a message from '.$this->from)
            ->line($this->message);
    }

    /**
     * Get the array representation of the notification
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subject' => $this->subject,
            'component' => 'User/UserNotification',
            'props' => [
                'message' => $this->message,
                'from' => $this->from,
            ],
        ];
    }

    /**
     * Get the Broadcast representation of the notification
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
