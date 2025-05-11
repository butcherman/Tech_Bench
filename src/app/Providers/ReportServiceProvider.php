<?php

namespace App\Providers;

use App\Contracts\ReportContract;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ReportServiceProvider extends ServiceProvider
{
    /**
     * Register all Reporting Services based on the Reporting Contract.
     */
    public function register(): void
    {
        $this->app->bind(ReportContract::class, function ($app) {

            $className = Str::studly($app->request->route('report'));
            $namespace = match ($app->request->route('group')) {
                // 'users' =>  "App\Services\Report\User\\$className",
                'customers' => "App\Services\Report\Customer\\$className",
                default => abort(404),
            };

            return new $namespace;
        });
    }
}
