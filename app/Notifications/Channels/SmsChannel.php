<?php

namespace App\Notifications\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class SmsChannel
{
    /**
     * Sene a SMS message via Twilio SMS
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toSms($notifiable);

        //  Send the SMS Message
        try {
            $client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
            $client->messages->create('+1'.$notifiable->phone, [
                'from' => config('services.twilio.from'),
                'body' => $message]);
        } catch (Exception $e) {
            Log::error('Unable to send SMS via Twilio: '.$e->getMessage());
        }
    }
}
