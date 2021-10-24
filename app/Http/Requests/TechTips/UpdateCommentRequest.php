<?php

namespace App\Http\Requests\TechTips;

use App\Models\TechTipComment;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', TechTipComment::find($this->route('comment')));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comment'    => 'required',
            'comment_id' => 'required|exists:tech_tip_comments,id',
        ];
    }
}
