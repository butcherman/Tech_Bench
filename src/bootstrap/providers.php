<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AppSettingsServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Providers\HorizonServiceProvider::class,
    App\Providers\ReportServiceProvider::class,
    App\Providers\TelescopeServiceProvider::class,
    SocialiteProviders\Manager\ServiceProvider::class,
    ZanySoft\Zip\ZipServiceProvider::class,
];
