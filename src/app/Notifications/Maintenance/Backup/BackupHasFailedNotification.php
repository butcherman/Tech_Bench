<?php

namespace App\Notifications\Maintenance\Backup;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Spatie\Backup\Notifications\Notifications\BackupHasFailedNotification as BaseNotification;

/**
 * @codeCoverageIgnore
 */
class BackupHasFailedNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->error()
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(__('backup::notifications.backup_failed_subject', [
                'application_name' => config('app.name'),
            ]))
            ->line(__('backup::notifications.backup_failed_body', [
                'application_name' => config('app.name'),
            ]))
            ->line(__('backup::notifications.exception_message', [
                'message' => $this->event->exception->getMessage(),
            ]))
            ->line(__('backup::notifications.exception_trace', [
                'trace' => $this->event->exception->getTraceAsString(),
            ]));

        $this->backupDestinationProperties()->each(
            fn ($value, $name) => $mailMessage->line("{$name}: {$value}")
        );

        return $mailMessage;
    }
}
