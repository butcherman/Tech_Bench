<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\SystemFiles;
use App\SystemTypes;
use App\SystemCategories;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemFilesTest extends TestCase
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
    
    //  Verify that a guest cannot store a new file
    public function testSubmitFileGuest()
    {
        $data = [
            'name'  => 'New Name',
            'sysID' => $this->systemType->sys_id,
            'type'  => 1,
            'file'  => $file = UploadedFile::fake()->image('avatar.jpg')
        ];
        
        $response = $this->post(route('system.system-files.store', $data));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech can store a new file
//    public function testSubmitFileTech()
//    {
//        $user = $this->getTech();
//        $data = [
//            'name'  => 'New Name',
//            'sysID' => $this->systemType->sys_id,
//            'type'  => 1,
//            'file'  => UploadedFile::fake()->create('avatar.pdf', 325)
//        ];
//        
//        $response = $this->actingAs($user)->post(route('system.system-files.store', $data));
//                
//        $response->assertStatus(302);
//        $response->assertJsonHas('success');
//    }
    
    //  Verify that a guest cannot edit a file information
    public function testUpdateFileGuest()
    {
        $sysFile = factory(SystemFiles::class)->create();
        
        $data = [
            'name' => 'New Name',
            'desc' => 'File Description'
        ];
        
        $response = $this->put(route('system.system-files.update', $sysFile->sys_file_id), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech can edit a file information
    public function testUpdateFileTech()
    {
        $sysFile = factory(SystemFiles::class)->create();
        
        $user = $this->getTech();
        $data = [
            'name' => 'New Name',
            'desc' => 'File Description'
        ];
        
        $response = $this->actingAs($user)->put(route('system.system-files.update', $sysFile->sys_file_id), $data);
                
        $response->assertSuccessful();
        $response->assertJsonStructure(['success']);
    }
    
    //  Verify that a guest cannot replace a file
    public function testReplaceFileGuest()
    {
        $sysFile = factory(SystemFiles::class)->create();
        $data = [
            'fileID' => $sysFile->sys_file_id,
            'file' => UploadedFile::fake()->image('random.jpg')
        ];
        
        $response = $this->post(route('system.replaceFile'), $data);
                    
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech can replace a file
    public function testReplaceFileTech()
    {
        $sysFile = factory(SystemFiles::class)->create();
        $user = $this->getTech();
        $data = [
            'fileID' => $sysFile->sys_file_id,
            'file' => UploadedFile::fake()->image('random.jpg')
        ];
        
        $response = $this->actingAs($user)->post(route('system.replaceFile'), $data);
        
        $response->assertSuccessful();
        $response->assertjsonStructure(['success']);
    }
    
    //  Verify that a guest cannot delete a file
    public function testDeleteFileGuest()
    {
        $sysFile = factory(SystemFiles::class)->create();
        
        $response = $this->delete(route('system.system-files.destroy', $sysFile->sys_file_id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Verify that a tech can delete a file
    public function testDeleteFileTech()
    {
        $sysFile = factory(SystemFiles::class)->create();
        $user = $this->getTech();
        
        $response = $this->actingAs($user)->delete(route('system.system-files.destroy', $sysFile->sys_file_id));
        
        $response->assertSuccessful();
        $response->assertJsonStructure(['success']);
    }
}
