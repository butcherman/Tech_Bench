<?php

use App\Files;
use App\FileLinks;
use App\FileLinkFiles;
//use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class FileLinksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $faker = new Faker;
        $faker = Faker\Factory::create('en_US');
        
        //  Create random file links for different users
        factory(FileLinks::class, 5)->create([
            'user_id' => 1,
            'note'    =>$faker->text
        ]);
        
        factory(FileLinks::class, 2)->create([
            'user_id' => 2
        ]);
        
        factory(FileLinks::class, 8)->create([
            'user_id' => 3
        ]);
        
        factory(FileLinks::class, 2)->create([
            'user_id' => 1,
            'cust_id' => 2387
        ]);
        
        //  Add files to the first file link
        $fileID = Files::create([
            'file_name' => 'file1.png',
            'file_link' => 'random/path'
        ]);
        FileLinkFiles::create([
            'link_id'  => 1,
            'file_id'  => $fileID->file_id,
            'user_id'  => 1,
            'added_by' => null,
            'upload'   => 0
        ]);
        
        $fileID = Files::create([
            'file_name' => 'file2.png',
            'file_link' => 'random/path'
        ]);
        FileLinkFiles::create([
            'link_id'  => 1,
            'file_id'  => $fileID->file_id,
            'user_id'  => 1,
            'added_by' => null,
            'upload'   => 0
        ]);
        
        $fileID = Files::create([
            'file_name' => 'file3.png',
            'file_link' => 'random/path'
        ]);
        FileLinkFiles::create([
            'link_id'  => 1,
            'file_id'  => $fileID->file_id,
            'user_id'  => 1,
            'added_by' => null,
            'upload'   => 0
        ]);
        
        $fileID = Files::create([
            'file_name' => 'file4.png',
            'file_link' => 'random/path'
        ]);
        FileLinkFiles::create([
            'link_id'  => 1,
            'file_id'  => $fileID->file_id,
            'user_id'  => null,
            'added_by' => $faker->name(),
            'upload'   => 1
        ]);
        
        $fileID = Files::create([
            'file_name' => 'file5.png',
            'file_link' => 'random/path'
        ]);
        FileLinkFiles::create([
            'link_id'  => 1,
            'file_id'  => $fileID->file_id,
            'user_id'  => null,
            'added_by' => $faker->name(),
            'upload'   => 1
        ]);
    }
}
