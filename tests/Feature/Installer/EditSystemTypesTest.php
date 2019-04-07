<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\SystemTypes;
use App\SystemCategories;
use App\SystemCustDataFields;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditSystemTypesTest extends TestCase
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
        
        SystemCustDataFields::create([
            'sys_id' => $this->systemType->sys_id,
            'data_type_id' => 1,
            'order' => 0
        ]);
    }
    
    /*
    *   Create new system page
    */
    
    //  Verify guest cannot visit page
    public function testEditPageGuest()
    {
        $response = $this->get(route('installer.systems.edit', $this->systemType->sys_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    // Verify tech cannot visit page
    public function testEditPageTech()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('installer.systems.edit', $this->systemType->sys_id));
        
        $response->assertStatus(401);
    }
    
    // Verify tech cannot visit page
    public function testEditPageReport()
    {
        $user = $this->getReport();
        $response = $this->actingAs($user)->get(route('installer.systems.edit', $this->systemType->sys_id));
        
        $response->assertStatus(401);
    }
    
    // Verify tech cannot visit page
    public function testEditPageAdmin()
    {
        $user = $this->getAdmin();
        $response = $this->actingAs($user)->get(route('installer.systems.edit', $this->systemType->sys_id));
        
        $response->assertStatus(401);
    }
    
    //  Verify that an installer user can visit the page
    public function testEditPageInstaller()
    {
        $user = $this->getInstaller();
        $response = $this->actingAs($user)->get(route('installer.systems.edit', $this->systemType->sys_id));
        
        $response->assertSuccessful();
        $response->assertViewIs('installer.editSystem');
    }
    
    //  Verify that a guest cannot submit the create system page
    public function testSubmitEditPageGuest()
    {
        $data = [
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
        
        $response = $this->put(route('installer.systems.update', $this->systemType->sys_id), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech cannot submit the create system page
    public function testSubmitEditPageTech()
    {
        $user = $this->getTech();
        $data = [
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
        
        $response = $this->actingAs($user)->put(route('installer.systems.update', $this->systemType->sys_id), $data);
                
        $response->assertStatus(401);
    }
    
    //  Verify that a report user cannot submit the create system page
    public function testSubmitEditPageReport()
    {
        $user = $this->getReport();
        $data = [
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
        
        $response = $this->actingAs($user)->put(route('installer.systems.update', $this->systemType->sys_id), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an admin cannot submit the create system page
    public function testSubmitEditPageAdmin()
    {
        $user = $this->getAdmin();
        $data = [
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
        
        $response = $this->actingAs($user)->put(route('installer.systems.update', $this->systemType->sys_id), $data);
        
        $response->assertStatus(401);
    }
    
    //  Verify that an installer can submit the create system page
    public function testSubmitEditPageInstaller()
    {
        $user = $this->getInstaller();
        $data = [
            'name'        => 'Updated System Name',
            'dataOptions' => []
        ];
        
        $response = $this->actingAs($user)->put(route('installer.systems.update', $this->systemType->sys_id), $data);
        
//        dd($this->app['session.store']);

        $response->assertSuccessful();
        $response->assertSessionHas(['success']);
    }
    
    //  Verify that an installer cannot submit with a missing name
    public function testSubmitEditPageNoName()
    {
        $user = $this->getInstaller();
        $data = [
            'dataOptions' => [
                0 => [
                    'value' => 1
                ],
                1 => [
                    'label' => 'Random Label'
                ]
            ] 
        ];
        
        $response = $this->actingAs($user)->put(route('installer.systems.update', $this->systemType->sys_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }
    
    //  Verify that an installer cannot submit with an invalid name
    public function testSubmitEditPageInvalidName()
    {
        $user = $this->getInstaller();
        $data = [
            'name'   => 'R@ndom & Nam3' 
        ];
        
        $response = $this->actingAs($user)->put(route('installer.systems.update', $this->systemType->sys_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }
}
