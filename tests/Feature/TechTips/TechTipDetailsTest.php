<?php

namespace Tests\Feature\TechTips;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\TechTips;
use App\TechTipSystems;
use App\TechTipComments;
use App\User;
use App\UserPermissions;
use Illuminate\Support\Facades\Notification;

class TechTipDetailsTest extends TestCase
{
    protected $tip;

    public function setUp():void
    {
        Parent::setup();

        $this->tip = factory(TechTips::class)->create();
        factory(TechTipSystems::class)->create([
            'tip_id' => $this->tip->tip_id,
        ]);
        factory(TechTipComments::class, 5)->create([
            'tip_id' => $this->tip->tip_id,
        ]);
    }

    //  Try to view the tech tip as a guest
    public function test_view_tip_as_guest()
    {
        $response = $this->get(route('tip.details', [$this->tip->tip_id, $this->tip->subject]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to view a tech tip
    public function test_view_tip()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('tip.details', [$this->tip->tip_id, $this->tip->subject]));

        $response->assertSuccessful();
        $response->assertViewIs('tips.details');
    }

    //  Try to add a comment to the tip as a guest
    public function test_comment_on_tip_as_guest()
    {
        $data = [
            'tip_id' => $this->tip->tip_id,
            'comment' => 'This is a comment',
        ];

        $response = $this->post(route('tip.comments.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to comment on a tip
    public function test_comment_on_tip()
    {
        Notification::fake();

        $data = [
            'tipID'   => $this->tip->tip_id,
            'comment' => 'This is a comment',
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->post(route('tip.comments.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Try to retrieve the comments on a tip as a guest
    public function test_get_tip_comments_as_guest()
    {
        $response = $this->get(route('tip.comments.show', $this->tip->tip_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to retrieve the comments on a tip
    public function test_get_tip_comments()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('tip.comments.show', $this->tip->tip_id));

        $response->assertSuccessful();
        $response->assertJsonStructure([['comment', 'comment_id', 'created_at', 'tip_id', 'updated_at', 'user', 'user_id']]);
    }

    //  Try to delete a tip as a guest
    public function test_delete_tip_as_guest()
    {
        $response = $this->delete(route('tips.destroy', $this->tip->tip_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to delete a tip without permissions
    public function test_delete_tip_without_permission()
    {
        $user     = $this->userWithoutPermission('Delete Tech Tip');
        $response = $this->actingAs($user)->delete(route('tips.destroy', $this->tip->tip_id));

        $response->assertStatus(403);
    }

    //  Try to delete a tip with the proper permissions
    public function test_delete_tip()
    {
        $user     = $this->getInstaller();
        $response = $this->actingAs($user)->delete(route('tips.destroy', $this->tip->tip_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
        $this->assertSoftDeleted($this->tip);
    }
}
