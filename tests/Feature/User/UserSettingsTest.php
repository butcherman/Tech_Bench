<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\User;

class UserSettingsTest extends TestCase
{
    public function test_invoke_guest()
    {
        $data = [
            'settingsData' => [
                [
                    'setting_type_id' => 1,
                    'value'           => false,
                ],
                [
                    'setting_type_id' => 2,
                    'value'           => true,
                ],
            ],
        ];

        $response = $this->post(route('update-settings'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_invoke()
    {
        $data = [
            'settingsData' => [
                [
                    'setting_type_id' => 1,
                    'value'           => false,
                ],
                [
                    'setting_type_id' => 2,
                    'value'           => true,
                ],
            ],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('update-settings'), $data);

        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Settings Updated']);
    }
}
