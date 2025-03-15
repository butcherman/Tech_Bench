<?php

namespace Tests\Browser\Pages\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    public function test_default_home_page(): void
    {
        DB::table('app_settings')->insert([
            'key' => 'services.azure.allow_login',
            'value' => json_encode(false),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Tech Login')
                ->assertSee('Forgot Password')
                ->assertSee('Login')
                ->assertDontSee('Login with Office 365');
        });
    }

    public function test_home_page_socialite_enabled(): void
    {
        DB::table('app_settings')->insert([
            'key' => 'services.azure.allow_login',
            'value' => json_encode(true),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Tech Login')
                ->assertSee('Forgot Password')
                ->assertSee('Login')
                ->assertSee('Login with Office 365');
        });
    }
}
