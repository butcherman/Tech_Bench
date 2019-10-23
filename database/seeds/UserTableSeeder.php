<?php

use App\User;
use App\UserPermissions;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
        UserPermissions::create(
        [
            'user_id'             => 2,
            'manage_users'        => 0,
            'run_reports'         => 1,
            'add_customer'        => 1,
            'deactivate_customer' => 0,
            'use_file_links'      => 1,
            'create_tech_tip'     => 1,
            'edit_tech_tip'       => 0,
            'delete_tech_tip'     => 0,
            'create_category'     => 0,
            'modify_category'     => 0
        ]);
        
        $emp2               = new User();
        $emp1->user_id      = 3;
        $emp2->username     = 'jeverett';
        $emp2->first_name   = 'Joshua';
        $emp2->last_name    = 'Everett';
        $emp2->email        = 'jdawg@em.com';
        $emp2->password     = bcrypt('password');
        $emp2->active       = 1;
        $emp2->save();
        UserPermissions::create(
        [
            'user_id'             => 3,
            'manage_users'        => 0,
            'run_reports'         => 1,
            'add_customer'        => 1,
            'deactivate_customer' => 0,
            'use_file_links'      => 1,
            'create_tech_tip'     => 1,
            'edit_tech_tip'       => 0,
            'delete_tech_tip'     => 0,
            'create_category'     => 0,
            'modify_category'     => 0
        ]);
        $emp3               = new User();
        $emp1->user_id      = 4;
        $emp3->username     = 'elindsay';
        $emp3->first_name   = 'Everett';
        $emp3->last_name    = 'Lindsay';
        $emp3->email        = 'mre@em.com';
        $emp3->password     = bcrypt('password');
        $emp3->active       = 1;
        $emp3->save();
        UserPermissions::create(
        [
            'user_id'             => 4,
            'manage_users'        => 0,
            'run_reports'         => 1,
            'add_customer'        => 1,
            'deactivate_customer' => 0,
            'use_file_links'      => 1,
            'create_tech_tip'     => 1,
            'edit_tech_tip'       => 0,
            'delete_tech_tip'     => 0,
            'create_category'     => 0,
            'modify_category'     => 0
        ]);
        
        $emp4               = new User();
        $emp4->user_id      = 5;
        $emp4->username     = 'bbob';
        $emp4->first_name   = 'Billy';
        $emp4->last_name    = 'Bob';
        $emp4->email        = 'bbob@em.com';
        $emp4->password     = bcrypt('password');
        $emp4->active       = 1;
        $emp4->save();
        UserPermissions::create(
        [
            'user_id'             => 5,
            'manage_users'        => 0,
            'run_reports'         => 1,
            'add_customer'        => 1,
            'deactivate_customer' => 0,
            'use_file_links'      => 1,
            'create_tech_tip'     => 1,
            'edit_tech_tip'       => 0,
            'delete_tech_tip'     => 0,
            'create_category'     => 0,
            'modify_category'     => 0
        ]);
    }
}
