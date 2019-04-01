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
        $emp1->user_id      = 2;
        $emp1->username     = 'admin';
        $emp1->first_name   = 'Administrator';
        $emp1->last_name    = 'User';
        $emp1->email        = 'user@em.com';
        $emp1->password     = bcrypt('password');
        $emp1->active       = 1;
        $emp1->save();
        $emp1->roles()->attach($role_installer);
        
        $emp2               = new User();
        $emp1->user_id      = 3;
        $emp2->username     = 'jeverett';
        $emp2->first_name   = 'Joshua';
        $emp2->last_name    = 'Everett';
        $emp2->email        = 'jdawg@em.com';
        $emp2->password     = bcrypt('password');
        $emp2->active       = 1;
        $emp2->save();
        $emp2->roles()->attach($role_report);
        
        $emp3               = new User();
        $emp1->user_id      = 4;
        $emp3->username     = 'elindsay';
        $emp3->first_name   = 'Everett';
        $emp3->last_name    = 'Lindsay';
        $emp3->email        = 'mre@em.com';
        $emp3->password     = bcrypt('password');
        $emp3->active       = 1;
        $emp3->save();
        $emp3->roles()->attach($role_tech);
        
        $emp4               = new User();
        $emp4->user_id      = 5;
        $emp4->username     = 'bbob';
        $emp4->first_name   = 'Billy';
        $emp4->last_name    = 'Bob';
        $emp4->email        = 'bbob@em.com';
        $emp4->password     = bcrypt('password');
        $emp4->active       = 1;
        $emp4->save();
        $emp4->roles()->attach($role_admin);
    }
}
