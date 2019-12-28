<?php

use Illuminate\Database\Seeder;

class TechTipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  Create 20 random tech tips
        factory(App\TechTips::class, 20)->create();
    }
}
