<?php

namespace App\Notifications\Maintenance\Backup;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFoundNotification as BaseNotification;

class UnhealthyBackupWasFoundNotification extends BaseNotification implements ShouldQueue
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
            ->subject(__('backup::notifications.unhealthy_backup_found_subject', [
                'application_name' => config('app.name'),
            ]))
            ->line(__('backup::notifications.unhealthy_backup_found_body', [
                'application_name' => config('app.name'),
                'disk_name' => 'backups',
            ]))
            ->line($this->problemDescription());

        $this->backupDestinationProperties()->each(
            function ($value, $name) use ($mailMessage) {
                $mailMessage->line("{$name}: {$value}");
            }
        );

        if ($this->failure()->wasUnexpected()) {
            $mailMessage
                ->line('Health check: '.$this->failure()->healthCheck()->name())
                ->line(__('backup::notifications.exception_message', [
                    'message' => $this->failure()->exception()->getMessage(),
                ]))
                ->line(__('backup::notifications.exception_trace', [
                    'trace' => $this->failure()->exception()->getTraceAsString(),
                ]));
        }

        return $mailMessage;
    }
}
