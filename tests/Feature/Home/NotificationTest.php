<?php

namespace Tests\Feature\Home;

use App\Models\TechTip;
use App\Models\User;
use App\Notifications\TechTips\NewTechTipNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        //  Create a new Tech Tip to generate a notification with
        $tip  = TechTip::factory()->create();
        $user = User::factory()->create();
        Notification::send($user, new NewTechTipNotification($tip));

        $response = $this->get(route('notifications.edit', $user->notifications()->first()->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_edit()
    {
        //  Create a new Tech Tip to generate a notification with
        $tip  = TechTip::factory()->create();
        $user = User::factory()->create();
        Notification::send($user, new NewTechTipNotification($tip));

        $response = $this->actingAs($user)->get(route('notifications.edit', $user->notifications()->first()->id));
        $response->assertSuccessful();
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        //  Create a new Tech Tip to generate a notification with
        $tip  = TechTip::factory()->create();
        $user = User::factory()->create();
        Notification::send($user, new NewTechTipNotification($tip));

        $response = $this->delete(route('notifications.destroy', $user->notifications()->first()->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy()
    {
        $tip  = TechTip::factory()->create();
        $user = User::factory()->create();
        Notification::send($user, new NewTechTipNotification($tip));

        $response = $this->actingAs($user)->delete(route('notifications.destroy', $user->notifications()->first()->id));
        $response->assertSuccessful();
    }
}
