<?php

// TODO - Refactor

namespace App\Http\Requests\Report\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UserReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('reports-link', $this->user());
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'start_date' => 'required',
            'end_date' => 'required',
            'user_list' => 'required',
        ];
    }
}
