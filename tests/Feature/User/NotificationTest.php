<?php

namespace Tests\Feature\User;

use Tests\TestCase;

class NotificationTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $data = [
            'action' => 'fetch',
        ];

        $response = $this->post(route('user.notifications'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  TODO - Finish Testing
}
