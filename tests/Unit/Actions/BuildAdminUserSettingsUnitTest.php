<?php

namespace Tests\Unit\Actions;

use App\Actions\BuildAdminUserSettings as ActionsBuildAdminUserSettings;
use Tests\TestCase;

/**
 * Testing ran on default database
 */
class BuildAdminUserSettingsUnitTest extends TestCase
{
    protected $object;

    public function setUp(): void
    {
        Parent::setUp();

        $this->object = new ActionsBuildAdminUserSettings;
    }

    /**
     * BuildTwoFaSettings Method
     */
    public function test_buildTwoFaSettings()
    {
        $twoFaSettings = $this->object->buildTwoFaSettings();
        $shouldBe = [
            'required' => false,
            'allow_save_device' => true,
            'allow_via_email' => true,
            'allow_via_sms' => false,
        ];

        $this->assertEquals($twoFaSettings, $shouldBe);
    }

    /**
     * bildOathSettings Method
     */
    public function test_buildOathSettings()
    {
        $oathSettings = $this->object->buildOathSettings();
        $shouldBe = [
            'allow_login' => false,
            'allow_register' => false,
            'tenant' => null,
            'client_id' => null,
            'client_secret' => null,
            'secret_expires' => null,
            'redirectUri' => config('app.url') . '/auth/callback',
        ];

        $this->assertEquals($oathSettings, $shouldBe);
    }

    /**
     * buildTwilioSettings Method
     */
    public function test_buildTwilioSettings()
    {
        $twilioSettings = $this->object->buildTwilioSettings();
        $shouldBe = [
            'sid' => null,
            'token' => null,
            'from' => null,
        ];

        $this->assertEquals($twilioSettings, $shouldBe);
    }
}
