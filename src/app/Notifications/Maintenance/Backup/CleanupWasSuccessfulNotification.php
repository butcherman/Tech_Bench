<?php

namespace App\Notifications\Maintenance\Backup;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Spatie\Backup\Notifications\Notifications\CleanupWasSuccessfulNotification as BaseNotification;

class CleanupWasSuccessfulNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(trans('backup::notifications.cleanup_successful_subject', [
                'application_name' => config('app.name')
            ]))
            ->line(trans('backup::notifications.cleanup_successful_body', [
                'application_name' => config('app.name'),
                'disk_name' => 'backups'
            ]));

        $this->backupDestinationProperties()->each(
            function ($value, $name) use ($mailMessage) {
                $mailMessage->line("{$name}: {$value}");
            }
        );

        return $mailMessage;
    }
}
