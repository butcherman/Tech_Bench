<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    public function test_base_home_page(): void
    {
        config(['services.azure.allow_login' => false]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Tech Login')
                ->assertSee('Forgot Password')
                ->assertSee('Login');
        });
    }
}
