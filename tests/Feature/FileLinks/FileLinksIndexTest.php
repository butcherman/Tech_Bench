<?php

namespace Tests\Feature\FileLinks;

use App\FileLinks;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileLinksIndexTest extends TestCase
{
    private $tech, $links;
    
    use RefreshDatabase;
    
    //  Create five random links
    public function setUp():void
    {
        Parent::setup();
        
        $this->tech  = $this->getTech();
        $this->links = factory(FileLinks::class, 5)->create([
            'user_id' => $this->tech->user_id
        ]);
    }
    
    //  Test visit index page as guest
    public function testIndexGuest()
    {
        $response = $this->get(route('links.index', $this->tech->user_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test visit index page as tech
    public function testIndexTech()
    {
        $response = $this->actingAs($this->tech)->get(route('links.index', $this->tech->user_id));
        
        $response->assertSuccessful();
        $response->assertViewIs('links.index');
    }
    
    //  Test JSON call to get file links as guest
    public function testGetLinksGuest()
    {
        $response = $this->get(route('links.user', $this->tech->user_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test JSON call to get the file links as tech
    public function testGetLinksTech()
    {
        $response = $this->actingAs($this->tech)->get(route('links.user', $this->tech->user_id));
                
        $response->assertSuccessful();
        $response->assertJsonStructure([['expire', 'link_id', 'link_name', 'link_hash', 'file_link_files_count', 'url', 'showClass']]);
    }
}
