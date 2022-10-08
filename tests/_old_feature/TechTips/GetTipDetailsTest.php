<?php

namespace Tests\Feature\TechTips;

use App\Models\TechTip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetTipDetailsTest extends TestCase
{
    public function test_invoke_guest()
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('tips.details', $tip->tip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permissions()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('tips.details', $tip->tip_id));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('tips.details', $tip->tip_id));
        $response->assertSuccessful();
        $response->assertJson([
            'author' => $tip->CreatedBy->full_name,
            'dateCreated' => date('M d, Y', strtotime($tip->created_at)),
            'lastEdited'  => $tip->updated_id !== null ? date('M d, Y', strtotime($tip->updated_at)) : null,
            'editedBy'    => $tip->updated_id !== null ? $tip->UpdatedBy->full_name : null,
            'views'       => $tip->views,
            'isPinned'    => $tip->sticky ? 'Yes' : 'No',
        ]);
    }
}
