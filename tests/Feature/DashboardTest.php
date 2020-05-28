<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Notifications\GenericDatabaseNotification;
use Illuminate\Support\Facades\Notification;


class DashboardTest extends TestCase
{
    protected $testUser;

    public function setUp():void
    {
        Parent::setup();

        $this->testUser = $this->getTech();
        for($i = 0; $i < 5; $i++)
        {
            Notification::send($this->testUser, new GenericDatabaseNotification('Notification number '.$i));
        }
    }

    public function test_index()
    {
        $response = $this->actingAs($this->getTech())->get(route('dashboard'));

        $response->assertSuccessful();
        $response->assertViewIs('dashboard');
        $this->assertAuthenticated();
    }

    public function test_index_guest()
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_about_page()
    {
        $response = $this->actingAs($this->getTech())->get(route('about'));

        $response->assertSuccessful();
        $response->assertViewIs('about');
        $this->assertAuthenticated();
    }

    public function test_about_page_guest()
    {
        $response = $this->get(route('about'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_get_notifications_guest()
    {
        $response = $this->get(route('get_notifications'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_get_notifications()
    {
        $response = $this->actingAs($this->testUser)->get(route('get_notifications'));

        $response->assertSuccessful();
        $response->assertJsonStructure([['id', 'type', 'notifiable_type', 'notifiable_id', 'data']]);
    }

    public function test_mark_notification_guest()
    {
        $response = $this->get(route('mark_notification', $this->testUser->notifications()->first()->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_mark_notification()
    {
        $response = $this->actingAs($this->testUser)->get(route('mark_notification', $this->testUser->notifications()->first()->id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_del_notification_guest()
    {
        $response = $this->delete(route('del_notification', $this->testUser->notifications()->first()->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_del_notification()
    {
        $response = $this->actingAs($this->testUser)->delete(route('del_notification', $this->testUser->notifications()->first()->id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
