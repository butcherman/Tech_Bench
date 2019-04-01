<?php

namespace Tests\Feature\Installer;

use Tests\TestCase;
use App\SystemCategories;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesTest extends TestCase
{
    public $category;
    
    use RefreshDatabase;
    
    /*
    *   Setup the test by seeding the database
    */
    public function setUp():void
    {
        Parent::setup();
        
        $this->category = factory(SystemCategories::class)->create();
    }
    
    /*
    *   Create category page
    */
    
    //  Verify guest cannot visit page
    public function testCreatePageGuest()
    {
        $response = $this->get(route('installer.categories.create'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    // Verify tech cannot visit page
    public function testCreatePageTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('installer.categories.create'));
        
        $response->assertStatus(401);
    }
    
    // Verify tech cannot visit page
    public function testCreatePageReport()
    {
        $user = $this->getReport();
        $response = $this->actingAs($user)->get(route('installer.categories.create'));
        
        $response->assertStatus(401);
    }
    
    // Verify tech cannot visit page
    public function testCreatePageAdmin()
    {
        $user = $this->getAdmin();
        $response = $this->actingAs($user)->get(route('installer.categories.create'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an installer user can visit the page
    public function testCreatePageInstaller()
    {
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->get(Route('installer.categories.create'));
        
        $response->assertSuccessful();
        $response->assertViewIs('installer.newCategory');
    }
    
    //  Verify that a guest cannot submit the create category page
    public function testSubmitCreatePageGuest()
    {
        $data = [
            'name' => 'A New Category Name'
        ];
        
        $response = $this->post(route('installer.categories.store'), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech cannot submit the create category page
    public function testSubmitCreatePageTech()
    {
        $user = $this->getTech();
        $data = [
            'name' => 'A New Category Name'
        ];
        
        $response = $this->actingAs($user)->post(route('installer.categories.store'), $data);
                
        $response->assertStatus(401);
    }
    
    //  Verify that a report user cannot submit the create category page
    public function testSubmitCreatePageReport()
    {
        $user = $this->getReport();
        $data = [
            'name' => 'A New Category Name'
        ];
        
        $response = $this->actingAs($user)->post(route('installer.categories.store'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an admin cannot submit the create category page
    public function testSubmitCreatePageAdmin()
    {
        $user = $this->getAdmin();
        $data = [
            'name' => 'A New Category Name'
        ];
        
        $response = $this->actingAs($user)->post(route('installer.categories.store'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an installer can submit the create category page
    public function testSubmitCreatePageInstaller()
    {
        $user = $this->getInstaller();
        $data = [
            'name' => 'A New Category Name'
        ];
        
        $response = $this->actingAs($user)->post(route('installer.categories.store'), $data);
        
//        dd($this->app['session.store']);
        
        $response->assertStatus(302);
        $response->assertSessionHas(['success']);
    }
    
    /*
    *   Edit category page
    */
    
    //  Verify guest cannot visit page
    public function testEditPageGuest()
    {   
        $response = $this->get(route('installer.categories.edit', 1));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    // Verify tech cannot visit page
    public function testEditPageTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('installer.categories.edit', 1));
        
        $response->assertStatus(401);
    }
    
    // Verify tech cannot visit page
    public function testEditPageReport()
    {
        $user = $this->getReport();
        $response = $this->actingAs($user)->get(route('installer.categories.edit', 1));
        
        $response->assertStatus(401);
    }
    
    // Verify tech cannot visit page
    public function testEditPageAdmin()
    {
        $user = $this->getAdmin();
        $response = $this->actingAs($user)->get(route('installer.categories.edit', 1));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an installer user can visit the page
    public function testEditPageInstaller()
    {
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->get(Route('installer.categories.edit', 1));
        
//        dd($this->app['session.store']);
        
        $response->assertSuccessful();
        $response->assertViewIs('installer.editCategory');
    }
    
    //  Verify that a guest cannot submit the create category page
    public function testSubmitEditPageGuest()
    {
        $data = [
            'name' => 'A New Category Name'
        ];
        
        $response = $this->put(route('installer.categories.update', 1), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech cannot submit the create category page
    public function testSubmitEditPageTech()
    {
        $user = $this->getTech();
        $data = [
            'name' => 'A New Category Name'
        ];
        
        $response = $this->actingAs($user)->put(route('installer.categories.update', 1), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that a report user cannot submit the create category page
    public function testSubmitEditPageReport()
    {
        $user = $this->getReport();
        $data = [
            'name' => 'A New Category Name'
        ];
        
        $response = $this->actingAs($user)->put(route('installer.categories.update', 1), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an admin cannot submit the create category page
    public function testSubmitEditPageAdmin()
    {
        $user = $this->getAdmin();
        $data = [
            'name' => 'A New Category Name'
        ];
        
        $response = $this->actingAs($user)->put(route('installer.categories.update', 1), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an installer can submit the create category page
    public function testSubmitEditPageInstaller()
    {
        $user = $this->getInstaller();
        $data = [
            'name' => 'A New Category Name'
        ];
        
        $response = $this->actingAs($user)->put(route('installer.categories.update', 1), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHas(['success']);
    }
}
