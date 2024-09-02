<?php

namespace App\Notifications\FileLinks;

use App\Models\FileLink;
use App\Models\FileLinkTimeline;
use App\Models\User;
use App\Models\UserSettingType;
use App\Traits\NotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class GuestFileUploadedNotification extends Notification
{
    use Queueable;
    use NotificationTrait;

    /**
     * Create a new notification instance.
     */
    public function __construct(public FileLink $link, public FileLinkTimeline $timeline)
    {
        Log::debug('Sending Guest File Uploaded Notification');
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
     */
    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('File Uploaded to File Link: ' . $this->link->link_name)
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line('A file has been uploaded to the File Link - ' .
                $this->link->link_name . '. by ' . $this->timeline->added_by)
            ->action('Click to View File', route('links.show', $this->link->link_id));
    }

    public function toBroadcast(): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'File Link Updated',
            'message' => 'A file was uploaded to a File Link',
            'href' => route('links.show', $this->link->link_id),
        ]);
    }
}
