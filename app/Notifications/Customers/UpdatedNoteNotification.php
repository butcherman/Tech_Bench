<?php

namespace App\Notifications\Customers;

use App\Models\Customer;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdatedNoteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $customer;

    /**
     * Create a new notification instance.
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Get the notification's delivery channels
     */
    public function via(object $notifiable): array
    {
        $settingId = UserSettingType::where('name', 'Receive Email Notifications')->first()->setting_type_id;
        $userSettings = UserSetting::where('user_id', $notifiable->user_id)->where('setting_type_id', $settingId)->first();

        if ($userSettings->value) {
            return ['mail', 'database'];
        }

        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('A Customer Note Has Been Updated')
        ->greeting('Hello '.$notifiable->full_name)
        ->line('A Customer Note was just updated for '.$this->customer->name)
        ->action('Click Here to view the Customer', url(route('customers.show', $this->customer->slug)))
        ->line('Note: You are receiving this notification because this customer is Bookmarked as a Favorite');
    }

    /**
     * Get the array representation of the notification
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subject' => 'A Customer Note Has Been Updated',
            'data'    => [
                'customer' => $this->customer->name,
                'slug'     => $this->customer->slug,
            ],
        ];
    }
}
