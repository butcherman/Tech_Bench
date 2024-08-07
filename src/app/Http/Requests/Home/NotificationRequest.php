<?php

namespace App\Http\Requests\Home;

use Illuminate\Foundation\Http\FormRequest;

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
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'action' => 'required|string',
            'idList' => 'required|array'
        ];
    }

    public function handleNotifications()
    {
        $messageList = $this->user()
            ->notifications()
            ->whereIn('id', $this->idList)
            ->get();

        foreach ($messageList as $message) {
            match ($this->action) {
                'read' => $message->markAsRead(),
                'delete' => $message->delete(),
            };
        }
    }
}
