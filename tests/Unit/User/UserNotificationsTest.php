<?php

namespace Tests\Unit\USer;

use App\Domains\User\UserNotifications;
use App\Notifications\GenericDatabaseNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\DB;

class UserNotificationsTest extends TestCase
{
    protected $testObj, $testUser;

    public function setUp():void
    {
        Parent::setup();

        $this->testUser = factory(User::class)->create()->makeVisible(['user_id', 'role_id', 'username'])->makeHidden('full_name', 'initials');
        $this->testObj  = new UserNotifications;
        for($i = 0; $i < 5; $i++)
        {
            Notification::send($this->testUser, new GenericDatabaseNotification('Notification number '.$i));
        }
    }

    public function test_mark_notification_read()
    {
        $testNotif = $this->testUser->notifications()->first();
        $res = $this->actingAs($this->testUser)->testObj->markNotificationRead($testNotif->id);

        $this->assertTrue($res);
        $this->assertNotNull(DB::select('SELECT "read" FROM `notifications` WHERE `id` = "'.$testNotif->id.'"'));
    }

    public function test_delete_notification()
    {
        $testNotif = $this->testUser->notifications()->first();
        $res       = $this->actingAs($this->testUser)->testObj->deleteNotification($testNotif->id);
        $this->assertTrue($res);
        $this->assertDatabaseMissing('notifications', ['id' => $testNotif->id]);
    }
}
