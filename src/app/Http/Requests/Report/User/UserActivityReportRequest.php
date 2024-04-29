<?php

namespace App\Http\Requests\Report\User;

use App\Models\User;
use App\Models\UserLogins;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UserActivityReportRequest extends FormRequest
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

    /**
     * Fetch Report Data
     */
    public function fetchReportData()
    {
        $reportData = [];

        foreach ($this->user_list as $username) {
            $user = User::whereUsername($username)->first();

            $loginData = UserLogins::where('user_id', $user->user_id)
                ->where('created_at', '>=', $this->start_date)
                ->where('created_at', '<=', $this->end_date)
                ->get();

            $reportData[$user->full_name] = $loginData;
        }

        return $reportData;
    }
}
