<?php

namespace Tests\Unit\Links;

use App\FileLinks;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LinksIndexTest extends TestCase
{
    use RefreshDatabase;
    
    //  Test get file links - no authorized user
    public function testGetLinksNoUser()
    {
        $response = $this->get(route('links.user', 1));
        
        $response->assertStatus(302);
    }
    
    //  Test get file links - authorized user - no results
    public function testGetLinks()
    {
        $user = $this->getTech();
        
        $response = $this->actingAs($user)->get(route('links.user', $user->user_id));
        
        $response->assertSuccessful();
    }
    
    //  Test get file links - authorized user - with results
    public function testGetMultipleLinks()
    {
        $user = $this->getTech();
        $data = factory(FileLinks::class, 5)->create([
            'user_id' => $user->user_id
        ]);
        
        //  Modify the expire format to match what we are expecting
        foreach($data as $d)
        {
            $d->expire = date('M d, Y', strtotime($d->expire));
        }
        
        $response = $this->actingAs($user)->get(route('links.user', $user->user_id));
        
        $response->assertSuccessful();
//        $response->assertJsonStructure(['link_id', 'link_name', 'link_hash']);
    }
}
