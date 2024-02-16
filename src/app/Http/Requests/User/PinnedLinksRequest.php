<?php

namespace App\Http\Requests\User;

use App\Models\UserPinnedLink;
use App\Traits\PinnedLinkTrait;
use Illuminate\Foundation\Http\FormRequest;

class PinnedLinksRequest extends FormRequest
{
    use PinnedLinkTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', UserPinnedLink::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'pin_name' => 'required|string',
            'model_name' => 'required|string',
            'model_route' => 'required|string',
            'model_key' => 'required|string',
        ];
    }

    /**
     * Add or Remove a Pinned Link
     */
    public function processPin()
    {
        $this->merge(['user_id' => $this->user()->user_id]);

        if ($this->isPinnedLink($this->pin_name, $this->model_name, $this->user_id)) {
            UserPinnedLink::where('user_id', $this->user_id)
                ->where('model_name', $this->model_name)
                ->where('model_key', $this->model_key)
                ->delete();
        } else {
            UserPinnedLink::create($this->toArray());
        }
    }
}
