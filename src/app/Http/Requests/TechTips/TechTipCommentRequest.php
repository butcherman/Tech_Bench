<?php

namespace App\Http\Requests\TechTips;

use App\Models\TechTipComment;
use Illuminate\Foundation\Http\FormRequest;

class TechTipCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->comment) {
            return $this->user()->can('update', $this->comment);
        }

        return $this->user()->can('create', TechTipComment::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'comment_data' => 'required|string',
        ];
    }
}
