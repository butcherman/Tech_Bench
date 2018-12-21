<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  Fetch user roles
        $role_installer = Role::where('name', 'installer')->first();
        $role_admin     = Role::where('name', 'admin')->first();
        $role_report    = Role::where('name', 'report')->first();
        $role_tech      = Role::where('name', 'tech')->first();
        
        //  Create the test users
        $emp1               = new User();
        $emp1->username     = 'admin';
        $emp1->first_name   = 'Administrator';
        $emp1->last_name    = 'User';
        $emp1->email        = 'ronbutcherii@gmail.com';
        $emp1->password     = bcrypt('password');
        $emp1->active       = 1;
        $emp1->save();
        $emp1->roles()->attach($role_installer);
        
        $emp2               = new User();
        $emp2->username     = 'jeverett';
        $emp2->first_name   = 'Joshua';
        $emp2->last_name    = 'Everett';
        $emp2->email        = 'jdawg@gmail.com';
        $emp2->password     = bcrypt('password');
        $emp2->active       = 1;
        $emp2->save();
        $emp2->roles()->attach($role_admin);
        
        $emp3               = new User();
        $emp3->username     = 'elinkday';
        $emp3->first_name   = 'Everett';
        $emp3->last_name    = 'Lindsay';
        $emp3->email        = 'mre@gmail.com';
        $emp3->password     = bcrypt('password');
        $emp3->active       = 1;
        $emp3->save();
        $emp3->roles()->attach($role_tech);
    }
}
