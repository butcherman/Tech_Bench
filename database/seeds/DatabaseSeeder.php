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
        // $this->call(UsersTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(SystemCategoriesSeeder::class);
        $this->call(SystemTypesSeeder::class);
        $this->call(SystemCustDataFieldsSeeder::class);
        $this->call(CustomersSeeder::class);
        $this->call(FileLinksSeeder::class);
    }
}
