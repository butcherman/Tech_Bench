<?php

namespace Tests\Feature\Links;

use App\FileLinks;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LinkDetailsTest extends TestCase
{
    public $fileLink, $owner;
    
    use RefreshDatabase;
    
    public function setUp():void
    {
        Parent::setUp();
        
        $this->owner = $this->getTech();
        $this->fileLink  = factory(FileLinks::class)->create([
            'user_id' => $this->owner->user_id
        ]);
    }
    
    //  Verify unauthorized user cannot visit the page
    public function testGuest()
    {
        $response = $this->get(route('links.details', [$this->fileLink->link_id, $this->fileLink->link_name]));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that the owner of the link can view the page
    public function testOwner()
    {
        $response = $this->actingAs($this->owner)->get(route('links.details', [$this->fileLink->link_id, $this->fileLink->link_name]));
        
        $response->assertSuccessful();
        $response->assertViewIs('links.details');
    }
    
    //  Verify a user can view a link they do not own
    public function testTech()
    {
        $user = $this->getTech();
        
        $response = $this->actingAs($user)->get(route('links.details', [$this->fileLink->link_id, $this->fileLink->link_name]));
        
        $response->assertSuccessful();
        $response->assertViewIs('links.details');
    }
}
