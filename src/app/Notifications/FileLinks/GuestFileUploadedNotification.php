<?php

namespace App\Notifications\FileLinks;

use App\Models\FileLink;
use App\Models\FileLinkTimeline;
use App\Models\UserSettingType;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GuestFileUploadedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public FileLink $link, public FileLinkTimeline $timeline)
    {
        //
    }

    /**
     * Get the notification's delivery channels
     */
    public function via(object $notifiable): array
    {
        $settingsId = UserSettingType::where('name', 'Receive Email Notifications')
            ->first()
            ->setting_type_id;
        $allow = (bool) $notifiable->UserSetting
            ->where('setting_type_id', $settingsId)
            ->first()
            ->value;

        return $allow ? ['mail', 'database'] : ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('File Uploaded to File Link: ' . $this->link->link_name)
            ->greeting('Hello,')
            ->line('A file has been uploaded to the File Link - ' .
                $this->link->link_name . '. by ' . $this->timeline->added_by)
            ->action('Click to View File', route('links.show', $this->link->link_id));
    }

    /**
     * Get the array representation of the notification
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subject' => 'New file uploaded to File Link ' . $this->link->link_name,
            'data' => [
                'link' => $this->link->toArray(),
                'name' => $this->timeline->added_by,
            ],
        ];
    }
}
