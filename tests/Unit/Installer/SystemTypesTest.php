<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\SystemTypes;
use App\SystemCategories;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemTypesTest extends TestCase
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
    
    //  Verify that a guest cannot get system information
    public function testGetDataGuest()
    {
        $response = $this->get(route('installer.systems.show', $this->systemType->sys_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech cannot get system information
    public function testGetDataTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('installer.systems.show', $this->systemType->sys_id));
        
        $response->assertStatus(401);
    }
    
    //  Verify that a report user cannot get system information
    public function testGetDataReport()
    {
        $user = $this->getReport();
        $response = $this->actingAs($user)->get(route('installer.systems.show', $this->systemType->sys_id));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an admin cannot get system information
    public function testGetDataAdmin()
    {
        $user = $this->getAdmin();
        $response = $this->actingAs($user)->get(route('installer.systems.show', $this->systemType->sys_id));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an installer can get system information
    public function testGetDataInstaller()
    {
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->get(route('installer.systems.show', $this->systemType->sys_id));
        
        $response->assertSuccessful();
        $response->assertJsonStructure(['name', 'data']);
    }
}
