<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Notifications\DatabaseNotification;

class DashboardTest extends TestCase
{
    protected $user, $notifications;

    public function setUp():void
    {
        Parent::setup();

        $this->user = $this->getTech();
        $this->notifications = factory(DatabaseNotification::class, 10)->create([
            "type" => "Namespace\ClassNameOfNotification",
            "notifiable_type" => "Notifiable\Model",
            "notifiable_id" =>$this->user->user_id, // id of the notifiable model
            "data" => [
                'type'    => 'warning',
                'message' => $this->user . ' did something cool',
                'link'    => '/dashboard',
            ]
        ]);
    }

    //  Try to visit the dashboard page as guest
    public function test_dashboard_page_as_guest()
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to visist the dashboard page
    public function test_dashboard_page()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertSuccessful();
        $response->assertViewIs('dashboard');
    }

    //  Try to visit the about page as guest
    public function test_about_page_as_guest()
    {
        $response = $this->get(route('about'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    // //  Try to visist the about page  TODO - why does this fail on Scrutinizer and Travis CI???
    // public function test_about_page()
    // {
    //     $user = $this->getTech();
    //     $response = $this->actingAs($user)->get(route('about'));

    //     $response->assertSuccessful();
    //     $response->assertViewIs('about');
    // }

    //  Try to retrieve some notifications as a guest
    public function test_get_notifications_as_guest()
    {
        $response = $this->get(route('getNotifications'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    // //  Try to retrieve a users notifications - TODO - How to set the session with the notifications?????
    // public function test_get_notifications()
    // {
    //     $response = $this->actingAs($this->user)->withSession(['notifications' => $this->notifications])->get(route('getNotifications'));

    //     dd($response);

    //     $response->assertSuccessful();
    //     $response->assertJsonStructure([['created_at', 'data', 'id', 'notifiable_id', 'notifiable_type', 'read_at', 'type', 'updated_at']]);
    // }

    //  Try to mark a notification read as guest
    public function test_mark_notification_as_guest()
    {
        $response = $this->get(route('markNotification', $this->notifications[0]->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to mark a notification as a different user
    public function test_mark_notification_not_mine()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('markNotification', $this->notifications[0]->id));

        $response->assertStatus(404);
    }

    //  Try to mark a notification
    public function test_mark_notification()
    {
        $response = $this->actingAs($this->user)->get(route('markNotification', $this->notifications[0]->id));

        $response->assertStatus(404);
    }

    //  Try to delete a notification as guest
    public function test_delete_notification_as_guest()
    {
        $response = $this->delete(route('delNotification', $this->notifications[0]->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to delete a notification as a different user
    public function test_delete_notification_not_mine()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->delete(route('delNotification', $this->notifications[0]->id));

        $response->assertStatus(404);
    }

    //  Try to delete a notification
    public function test_delete_notification()
    {
        $response = $this->actingAs($this->user)->delete(route('delNotification', $this->notifications[0]->id));

        $response->assertStatus(404);
    }
}
