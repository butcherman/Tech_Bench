<?php

namespace Tests\Unit\Links;

use App\FileLinks;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LinkFilesTest extends TestCase
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
    
    //  Test adding a file to a link as guest
    public function testAddFileGuest()
    {
        $file = UploadedFile::fake()->image('anImage.jpg');
        
        $data = [
            'file' => [$file]
        ];
        
        $response = $this->post(route('links.files', [$this->fileLink->link_id, 'down']), $data);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test adding a file to a link 
    public function testAddFile()
    {
        $file = UploadedFile::fake()->image('anImage.jpg');
        
        $data = [
            'file' => [$file]
        ];
        
        $response = $this->actingAs($this->owner)->post(route('links.files', [$this->fileLink->link_id, 'down']), $data);
        
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
    
    //  Test getting the files for the link as guest
    public function testGetFilesGuest()
    {
        $response = $this->get(route('links.files', [$this->fileLink->link_id, 'down']));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
    
    //  Test getting the files for the link
    public function testGetFiles()
    {
        $response = $this->actingAs($this->owner)->get(route('links.files', [$this->fileLink->link_id, 'down']));
        
        $response->assertSuccessful();
    }
}
