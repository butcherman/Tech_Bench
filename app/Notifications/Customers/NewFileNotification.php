<?php

namespace App\Notifications\Customers;

use App\Models\Customer;
use App\Models\UserSettingType;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewFileNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $cust;

    /**
     * Create a new notification instance
     */
    public function __construct(Customer $cust)
    {
        $this->cust = $cust;
    }

    /**
     * Get the notification's delivery channels
     */
    public function via($notifiable)
    {
        $settingId = UserSettingType::where('name', 'Receive Email Notifications')->first()->setting_type_id;
        $allow     = $notifiable->UserSetting->where('setting_type_id', $settingId)->first()->value;

        if($allow)
        {
            return ['mail', 'database'];
        }

        return ['database'];
    }

    /**
     * Get the mail representation of the notification
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('A New Customer File Has Been Created')
                    ->greeting('Hello '.$notifiable->full_name)
                    ->line('A new Customer File was just created for '.$this->cust->name)
                    ->action('Click Here to view the Customer', url(route('customers.show', $this->cust->slug)))
                    ->line('Note: You are receiving this notification because this customer is Bookmarked as a Favorite');
    }

    /**
     * Get the array representation of the notification
     */
    public function toArray($notifiable)
    {
        return [
            'subject' => 'A New Customer Contact Has Been Created',
            'data'    => [
                'customer' => $this->cust->name,
                'slug'     => $this->cust->slug,
            ],
        ];
    }
}
