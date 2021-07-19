<?php

namespace App\Http\Requests\TechTips;

use App\Models\TechTipComment;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', TechTipComment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comment' => 'required',
            'tip_id'  => 'required|exists:tech_tips',
        ];
    }
}
