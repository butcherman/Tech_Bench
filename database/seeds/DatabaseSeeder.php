<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //  TODO:  add seeders for demo setup
         $this->call(UserTableSeeder::class);
         $this->call(EquipmentSeeder::class);
         $this->call(CustomerSeeder::class);
         $this->call(TechTipsSeeder::class);
         $this->call(ConfigSeeder::class);
    }
}
