<?php

use App\Settings;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create(['key' => 'app.timezone', 'value' => 'America/Los_Angeles']);
        Settings::create(['key' => 'user.passExpires', 'value' => '30']);
    }
}
