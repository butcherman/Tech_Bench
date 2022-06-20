<?php

namespace Tests\Feature\Home;

use Tests\TestCase;

use Illuminate\Support\Facades\Notification;

use App\Models\User;
use App\Models\TechTip;
use App\Models\UserSetting;
use App\Notifications\TechTips\NewTechTipNotification;

class NotificationTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        //  Create a new Tech Tip to generate a notification with
        $tip  = TechTip::factory()->create();
        $user = User::factory()->create();

        UserSetting::create([
            'user_id'         => $user->user_id,
            'setting_type_id' => 1,
            'value'           => 1,
        ]);

        Notification::send($user, new NewTechTipNotification($tip));

        $response = $this->post(route('notifications'), [
            'action' => 'read',
            'list' => [$user->notifications()->first()->id]
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_mark()
    {
        //  Create a new Tech Tip to generate a notification with
        $tip  = TechTip::factory()->create();
        $user = User::factory()->create();

        UserSetting::create([
            'user_id'         => $user->user_id,
            'setting_type_id' => 1,
            'value'           => 1,
        ]);

        Notification::send($user, new NewTechTipNotification($tip));
        $response = $this->actingAs($user)->post(route('notifications'), [
            'action' => 'read',
            'list' => [$user->notifications()->first()->id]
        ]);
        $response->assertSuccessful();
    }

    public function test_invoke_delete()
    {
        //  Create a new Tech Tip to generate a notification with
        $tip  = TechTip::factory()->create();
        $user = User::factory()->create();

        UserSetting::create([
            'user_id'         => $user->user_id,
            'setting_type_id' => 1,
            'value'           => 1,
        ]);

        Notification::send($user, new NewTechTipNotification($tip));
        $response = $this->actingAs($user)->post(route('notifications'), [
            'action' => 'delete',
            'list' => [$user->notifications()->first()->id]
        ]);
        $response->assertSuccessful();
    }
}
