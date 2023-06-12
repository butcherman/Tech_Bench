<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'action' => 'required|string',
            'list' => 'nullable|array',
        ];
    }

    /**
     * Go through notifications and delete or mark as read
     */
    public function processNotifications()
    {
        if($this->input('list'))
        {
            foreach($this->input('list') as $msg) {
                $notification = $this->user()->notifications()->where('id', $msg)->first();
                //  It is possible the notification was handled and deleted in a previous request
                if(empty($notification)) {
                    return true;
                }

                if($this->input('action') === 'mark') {
                    $notification->markAsRead();
                    Log::debug('Marked Notification ID '.$msg.' for '.$this->user()->username.' as read');
                } elseif($this->input('action') === 'delete') {
                    $notification->delete();
                    Log::debug('Deleted Notification ID '.$msg.' for '.$this->user()->username);
                }
            }
        }

        return true;
    }
}
