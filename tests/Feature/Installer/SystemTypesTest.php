<?php

namespace Tests\Feature;

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
    
    /*
    *   Create new system page
    */
    
    //  Verify guest cannot visit page
    public function testCreatePageGuest()
    {
        $response = $this->get(route('installer.systems.create'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    // Verify tech cannot visit page
    public function testCreatePageTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('installer.systems.create'));
        
        $response->assertStatus(401);
    }
    
    // Verify tech cannot visit page
    public function testCreatePageReport()
    {
        $user = $this->getReport();
        $response = $this->actingAs($user)->get(route('installer.systems.create'));
        
        $response->assertStatus(401);
    }
    
    // Verify tech cannot visit page
    public function testCreatePageAdmin()
    {
        $user = $this->getAdmin();
        $response = $this->actingAs($user)->get(route('installer.systems.create'));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an installer user can visit the page
    public function testCreatePageInstaller()
    {
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->get(Route('installer.systems.create'));
        
        $response->assertSuccessful();
        $response->assertViewIs('installer.newSystem');
    }
    
    //  Verify that a guest cannot submit the create system page
    public function testSubmitCreatePageGuest()
    {
        $data = [
            'category' => $this->category->cat_id,
            'name'   => 'New System Name',
            'dataOptions' => [
                0 => [
                    'value' => 1
                ],
                1 => [
                    'label' => 'Random Label'
                ]
            ] 
        ];
        
        $response = $this->post(route('installer.systems.store'), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech cannot submit the create system page
    public function testSubmitCreatePageTech()
    {
        $user = $this->getTech();
        $data = [
            'category' => $this->category->cat_id,
            'name'   => 'New System Name',
            'dataOptions' => [
                0 => [
                    'value' => 1
                ],
                1 => [
                    'label' => 'Random Label'
                ]
            ] 
        ];
        
        $response = $this->actingAs($user)->post(route('installer.systems.store'), $data);
                
        $response->assertStatus(401);
    }
    
    //  Verify that a report user cannot submit the create system page
    public function testSubmitCreatePageReport()
    {
        $user = $this->getReport();
        $data = [
            'category' => $this->category->cat_id,
            'name'   => 'New System Name',
            'dataOptions' => [
                0 => [
                    'value' => 1
                ],
                1 => [
                    'label' => 'Random Label'
                ]
            ] 
        ];
        
        $response = $this->actingAs($user)->post(route('installer.systems.store'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an admin cannot submit the create system page
    public function testSubmitCreatePageAdmin()
    {
        $user = $this->getAdmin();
        $data = [
            'category' => $this->category->cat_id,
            'name'   => 'New System Name',
            'dataOptions' => [
                0 => [
                    'value' => 1
                ],
                1 => [
                    'label' => 'Random Label'
                ]
            ] 
        ];
        
        $response = $this->actingAs($user)->post(route('installer.systems.store'), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an installer can submit the create system page
    public function testSubmitCreatePageInstaller()
    {
        $user = $this->getInstaller();
        $data = [
            'category' => $this->category->cat_id,
            'name'   => 'New System Name',
            'dataOptions' => [
                0 => [
                    'value' => 1
                ],
                1 => [
                    'label' => 'Random Label'
                ]
            ] 
        ];
        
        $response = $this->actingAs($user)->post(route('installer.systems.store'), $data);

        $response->assertSuccessful();
        $response->assertSessionHas(['success']);
    }
    
    //  Verify that an installer cannot submit with an invalid category
    public function testSubmitCreatePagebadCategory()
    {
        $user = $this->getInstaller();
        $data = [
            'category' => 'A Category',
            'name'   => 'New System Name',
            'dataOptions' => [
                0 => [
                    'value' => 1
                ],
                1 => [
                    'label' => 'Random Label'
                ]
            ] 
        ];
        
        $response = $this->actingAs($user)->post(route('installer.systems.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['category']);
    }
    
    //  Verify that an installer cannot submit with a missing name
    public function testSubmitCreatePageNoName()
    {
        $user = $this->getInstaller();
        $data = [
            'category' => $this->category->cat_id,
            'dataOptions' => [
                0 => [
                    'value' => 1
                ],
                1 => [
                    'label' => 'Random Label'
                ]
            ] 
        ];
        
        $response = $this->actingAs($user)->post(route('installer.systems.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }
    
    //  Verify that an installer cannot submit with an invalid name
    public function testSubmitCreatePageInvalidName()
    {
        $user = $this->getInstaller();
        $data = [
            'category' => $this->category->cat_id,
            'name'   => 'R@ndom & Nam3',
            'dataOptions' => [
                0 => [
                    'value' => 1
                ],
                1 => [
                    'label' => 'Random Label'
                ]
            ] 
        ];
        
        $response = $this->actingAs($user)->post(route('installer.systems.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }
    
    //  Verify that an installer cannot submit without any data options
    public function testSubmitCreatePageNoDataOptions()
    {
        $user = $this->getInstaller();
        $data = [
            'category' => $this->category->cat_id,
            'name'   => 'New System Name',
        ];
        
        $response = $this->actingAs($user)->post(route('installer.systems.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['dataOptions']);
    }
}
