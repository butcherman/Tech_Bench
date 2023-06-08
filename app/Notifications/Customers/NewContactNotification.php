<?php

namespace App\Notifications\Customers;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $customer;
    protected $contact;

    /**
     * Create a new notification instance.
     */
    public function __construct(Customer $customer, CustomerContact $contact)
    {
        $this->customer = $customer;
        $this->contact = $contact;
    }

    /**
     * Get the notification's delivery channels.
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
            ->subject($this->customer->name.' has a new Contact')
            ->greeting('Hello '.$notifiable->full_name)
            ->line($this->contact->name.' was just created as a contact for '.$this->customer->name)
            ->action('Click Here to view the Customer and get more information', url(route('customers.show', $this->customer->slug)))
            ->line('Note: You are receiving this notification because '.$this->customer->name.' is Bookmarked as a Favorite');
    }

    /**
     * Get the array representation of the notification
     */
    public function toArray(): array
    {
        return [
            'subject' => $this->customer->name.' has a new Contact',
            'component' => 'Customer/ContactNotification',
            'props' => [
                'new' => true,
                'customer' => $this->customer,
                'contact' => $this->contact,
            ],
        ];
    }
}
