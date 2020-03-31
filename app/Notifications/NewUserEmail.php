<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserEmail extends Notification implements ShouldQueue
{
    use Queueable;
    protected $user, $hash;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $hash)
    {
        //
        $this->user = $user;
        $this->hash = $hash;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(/** @scrutinizer ignore-unused */$notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(
    /** @scrutinizer ignore-unused */
    $notifiable)
    {
        return (new MailMessage)
        ->greeting('Hello '.$this->user->full_name)
                    ->line('A new '.config('app.name').' account has been created for you.')
                    ->line('Your new username is:  **'.$this->user->username.'**')
                    ->line('You can click the link below to finsh setting up your account.')
                    ->action('Setup Account', url(route('initialize', $this->hash)));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(
    /** @scrutinizer ignore-unused */
    $notifiable)
    {
        return [
            //
        ];
    }
}
