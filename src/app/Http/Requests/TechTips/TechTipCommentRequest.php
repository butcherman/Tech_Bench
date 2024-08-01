<?php

namespace App\Http\Requests\TechTips;

use App\Features\TechTipComment;
use Illuminate\Foundation\Http\FormRequest;

class TechTipCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->features()->active(TechTipComment::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'comment' => 'required|string',
        ];
    }
}
