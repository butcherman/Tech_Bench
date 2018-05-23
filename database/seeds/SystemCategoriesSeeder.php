<?php

use Illuminate\Database\Seeder;
use App\SystemCategories;

class SystemCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Test Seeders
        $cat1           = new SystemCategories();
        $cat1->cat_id   = 1;
        $cat1->name     = 'Phones';
        $cat1->save();
        
        $cat2           = new SystemCategories();
        $cat2->cat_id   = 2;
        $cat2->name     = 'Security';
        $cat2->save();
        
        $cat3           = new SystemCategories();
        $cat3->cat_id   = 3;
        $cat3->name     = 'Bells';
        $cat3->save();
    }
}
