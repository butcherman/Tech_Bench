<?php

namespace Tests\Feature;

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
    
    //  Verify that a guest cannot visit index page
    public function testIndexGuest()
    {
        $response = $this->get(route('system.index'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech can visit index page
    public function testIndexTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('system.index'));
        
        $response->assertSuccessful();
        $response->assertViewIs('system.index');
    }
    
    //  Verify that a guest cannot visit the select system page
    public function testCategoryGuest()
    {
        $response = $this->get(route('system.select', $this->category->name));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech can visit select system page
    public function testCategoryTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('system.select', $this->category->name));

        $response->assertSuccessful();
        $response->assertViewIs('system.selectSystem');
    }
    
    //  Verify that a guest cannot visit the details page
    public function testDetailsGuest()
    {
        $response = $this->get(route('system.details', [$this->category->name, $this->systemType->name]));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech can visit details page
    public function testDetailsTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('system.details', [$this->category->name, $this->systemType->name]));
        
//        echo 'category - '.$this->category->name.'  System - '.$this->systemType->name;
//        die();

        $response->assertSuccessful();
        $response->assertViewIs('system.details');
    }
}




////////////////////////////////////////
//////////////////////////////////////
/////////////////////////////////////////