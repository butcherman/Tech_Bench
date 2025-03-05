<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Process;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;

abstract class DuskTestCase extends BaseTestCase
{
    use DatabaseTruncation;

    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        // if (! static::runningInSail()) {
        //     static::startChromeDriver(['--port=9515']);
        // }

    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        // Build Vite files to ensure they are up to date
        Process::path('/app')->run('npm run build');

        // Remove hot file
        Process::path('/app/public')->run('rm hot');

        $this->withoutVite();

        $server = 'http://selenium:4444';

        $options = (new ChromeOptions)->addArguments([
            '--window-size=1920,1080',
            '--whitelisted-ips=""',
            '--ignore-certificate-errors',
        ]);

        $capabilities = DesiredCapabilities::chrome()
            ->setCapability(ChromeOptions::CAPABILITY, $options);

        return RemoteWebDriver::create(
            $server,
            $capabilities,
        );
    }
}
