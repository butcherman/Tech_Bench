<?php

namespace App\Http\Controllers\Admin\Backups;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;

class BackupIndexController extends Controller
{
    /**
     * App Backups Landing Page
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Backups/Index', [
            'rules' => [
                'daily'   => config('backup.cleanup.default_strategy.keep_daily_backups_for_days'),
                'weekly'  => config('backup.cleanup.default_strategy.keep_weekly_backups_for_weeks'),
                'monthly' => config('backup.cleanup.default_strategy.keep_monthly_backups_for_months'),
                'yearly'  => config('backup.cleanup.default_strategy.keep_yearly_backups_for_years'),
            ],
            'settings' => [
                'enable'   => (bool) config('app.backups.enabled'),
                'password' => config('backup.backup.password'),
                'email'    => config('backup.notifications.mail.to'),
            ]
        ]);
    }
}
