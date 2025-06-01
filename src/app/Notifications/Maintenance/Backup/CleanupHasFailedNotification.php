<?php

namespace App\Notifications\Maintenance\Backup;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Spatie\Backup\Notifications\Notifications\CleanupHasFailedNotification as BaseNotification;

class CleanupHasFailedNotification extends BaseNotification implements ShouldQueue
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
            ->subject(trans('backup::notifications.cleanup_failed_subject', [
                'application_name' => config('app.name')
            ]))
            ->line(__('backup::notifications.cleanup_failed_body', [
                'application_name' => config('app.name')
            ]))
            ->line(__('backup::notifications.exception_message', [
                'message' => $this->event->exception->getMessage()
            ]))
            ->line(__('backup::notifications.exception_trace', [
                'trace' => $this->event->exception->getTraceAsString()
            ]));

        $this->backupDestinationProperties()->each(
            function ($value, $name) use ($mailMessage) {
                $mailMessage->line("{$name}: {$value}");
            }
        );

        return $mailMessage;
    }
}
