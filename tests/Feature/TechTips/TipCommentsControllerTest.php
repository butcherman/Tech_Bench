<?php

namespace Tests\Feature\TechTips;

use App\TechTipComments;
use App\TechTips;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TipCommentsControllerTest extends TestCase
{
    public function test_store()
    {
        $tip     = factory(TechTips::class)->create();
        $comment = factory(TechTipComments::class)->make();
        $data    = [
            'tip_id'  => $tip->tip_id,
            'comment' => $comment->comment,
        ];

        $response = $this->actingAs($this->getTech())->post(route('tips.comments.store'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_store_guest()
    {
        $tip     = factory(TechTips::class)->create();
        $comment = factory(TechTipComments::class)->make();
        $data    = [
            'tip_id'  => $tip->tip_id,
            'comment' => $comment->comment,
        ];

        $response = $this->post(route('tips.comments.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $tip = factory(TechTips::class)->create();
        factory(TechTipComments::class, 5)->create(['tip_id' => $tip->tip_id]);

        $response = $this->actingAs($this->getTech())->get(route('tips.comments.show', $tip->tip_id));
        $response->assertSuccessful();
        $response->assertJsonCount(5);
    }

    public function test_show_guest()
    {
        $tip = factory(TechTips::class)->create();
        factory(TechTipComments::class, 5)->create(['tip_id' => $tip->tip_id]);

        $response = $this->get(route('tips.comments.show', $tip->tip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy()
    {
        $comment = factory(TechTipComments::class)->create();

        $response = $this->actingAs($this->getTech())->delete(route('tips.comments.destroy', $comment->comment_id));
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_destroy_guest()
    {
        $comment = factory(TechTipComments::class)->create();

        $response = $this->delete(route('tips.comments.destroy', $comment->comment_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
