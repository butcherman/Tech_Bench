<?php

namespace Tests\Unit\Systems;

use Tests\TestCase;
use App\SystemTypes;
use App\SystemCategories;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemsTest extends TestCase
{
    public $category, $systemType;
    
    use RefreshDatabase;
    
    /*
    *   Setup the test by seeding the database
    */
    public function setUp():void
    {
        Parent::setup();
        
        $this->category   = factory(SystemCategories::class)->create();
        $this->systemType = factory(SystemTypes::class)->create();
    }
    
    //  Verify that a guest cannot get system files
    public function testGetFilesGuest()
    {
        $response = $this->get(route('system.system-files.show', $this->systemType->sys_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech user can get system files
    public function testGetFilesTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('system.system-files.show', $this->systemType->sys_id));
        
        $response->assertSuccessful();
    }
}
