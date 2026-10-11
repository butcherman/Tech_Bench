<?php

use App\Providers\AppServiceProvider;
use App\Providers\AppSettingsServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\ReportServiceProvider;
use App\Providers\TelescopeServiceProvider;
use SocialiteProviders\Manager\ServiceProvider;
use ZanySoft\Zip\ZipServiceProvider;

return [
    AppServiceProvider::class,
    AppSettingsServiceProvider::class,
    FortifyServiceProvider::class,
    HorizonServiceProvider::class,
    ReportServiceProvider::class,
    TelescopeServiceProvider::class,
    ServiceProvider::class,
    ZipServiceProvider::class,
];
