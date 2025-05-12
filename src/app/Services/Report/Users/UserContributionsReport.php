<?php

namespace App\Services\Report\Users;

use App\Models\User;
use Illuminate\Support\Collection;

class UserContributionsReport extends UserReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamForm = 'UserContributionsReportForm';
        $this->reportDataPage = 'UserContributionsReport';
        $this->reportParamProps = ['user-list' => User::all()];
    }

    /**
     * Validate the request to run the report.
     */
    public function getValidationParams(): array
    {
        return [
            'start_date' => ['required', 'string'],
            'end_date' => ['required', 'string'],
            'user_list' => ['required', 'array'],
        ];
    }

    /**
     * Run the report
     */
    public function generateReportData(Collection $reportParams): array
    {
        $data = [];
        $start = $reportParams->get('start_date');
        $end = $reportParams->get('end_date');
        $modelList = [
            'CustomerNote@created_by',
            'CustomerNote@updated_by',
            'CustomerFile@user_id',
            'TechTip@user_id',
            'TechTip@updated_id',
            'TechTipComment@user_id',
        ];

        foreach ($reportParams->get('user_list') as $username) {
            $user = User::whereUsername($username)->first();

            $contributions = [];
            foreach ($modelList as $model) {
                $contributions[$model] = $this->getModelContributions(
                    $user,
                    $model,
                    $start,
                    $end
                );
            }

            $data[$user->full_name] = $contributions;
        }

        return $data;
    }

    /**
     * Get the users contributions for a specific model
     */
    protected function getModelContributions(User $user, string $model, string $start, string $end)
    {
        [$modelName, $fieldName] = explode('@', $model);
        $namespace = 'App\Models\\' . $modelName;

        return $namespace::where($fieldName, $user->user_id)
            ->whereBetween('created_at', [
                $start,
                $end
            ])->count();
    }
}
