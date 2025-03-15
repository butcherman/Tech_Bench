<?php

namespace Tests\Browser\Pages\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TwoFaTest extends DuskTestCase
{
    // public function test_two_fa_default(): void
    // {
    //     DB::table('app_settings')->insert([
    //         'key' => 'auth.twoFa.required',
    //         'value' => json_encode(true)
    //     ]);

    //     $this->browse(function (Browser $browser) {
    //         $browser->loginAs(User::find(1))
    //             ->visit(route('2fa.show'))
    //             ->assertSee('Two Factor Authentication')
    //             ->assertSee('Remember This Device')
    //             ->assertSee('Send New Verification Code');
    //     });
    // }

    // public function test_two_fa_no_remember_device(): void
    // {
    //     //
    // }
}
