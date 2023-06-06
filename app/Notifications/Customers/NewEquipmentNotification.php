<?php

namespace App\Notifications\Customers;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewEquipmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $customer;
    protected $equipment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Customer $customer, CustomerEquipment $equipment)
    {
        $this->customer = $customer;
        $this->equipment = $equipment;

        Log::debug('New Equipment Notification Called');
    }

    /**
     * Get the notification's delivery channels
     * If the User's settings has Email notifications turned off, we will only notify via db
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
            ->subject('New Customer Equipment Created For '.$this->customer->name)
            ->greeting('Hello '.$notifiable->full_name)
            ->line($this->equipment->name.'has just been added to '.$this->customer->name)
            ->action('Click Here to view the Customer', url(route('customers.show', $this->customer->slug)))
            ->line('Note: You are receiving this notification because this customer is Bookmarked as a Favorite');
    }

    /**
     * Get the array representation of the notification
     */
    public function toArray(): array
    {
        return [
            'subject' => 'New Customer Equipment Created For '.$this->customer->name,
            'component' => 'Customers/EquipmentNotification',
            'props' => [
                'new' => true,
                'customer' => $this->customer->name,
                'slug' => $this->customer->slug,
                'equipment' => $this->equipment->name,
            ],
        ];
    }
}
