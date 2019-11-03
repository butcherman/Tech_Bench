<?php

use App\User;
use App\TechTips;
use App\TechTipFiles;
use App\TechTipSystems;
use App\UserPermissions;
use App\PhoneNumberTypes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatesForVersion50 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  Remove the Navbar view
        DB::statement('DROP VIEW IF EXISTS `navbar_view`');
        
        //  Update the icons in the phone number types table
        PhoneNumberTypes::find(1)->update(
        [
            'icon_class' => 'ti-home'
        ]);
        PhoneNumberTypes::find(2)->update(
        [
            'icon_class' => 'ti-briefcase'
        ]);
        PhoneNumberTypes::find(3)->update(
        [
            'icon_class' => 'ti-mobile'
        ]);
        
        //  Add the 'password expires' column to the users table
        if(!Schema::hasColumn('users', 'password_expires'))
        {
            Schema::table('users', function(Blueprint $table) {
                $table->timestamp('password_expires')
                    ->nullable()
                    ->after('active');
            });
        }
        
        //  Add the 'hidden' column to the system_cust_data_types table
        if(!Schema::hasColumn('system_cust_data_types', 'hidden'))
        {
            Schema::table('system_cust_data_types', function(Blueprint $table) {
                $table->boolean('hidden')
                    ->default(0)
                    ->after('name');
            });
        }
        
        //  Add the cust id and note colunns to the file_links table
        if(!Schema::hasColumn('file_links', 'cust_id'))
        {
            Schema::table('file_links', function(Blueprint $table) {
                $table->integer('cust_id')
                    ->unsigned()
                    ->nullable()
                    ->after('user_id');
                $table->foreign('cust_id')->references('cust_id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            });
        }
        if(!Schema::hasColumn('file_links', 'note'))
        {
            Schema::table('file_links', function(Blueprint $table) {
                $table->longText('note')
                    ->nullable()
                    ->after('link_name');
            });
        }
        
        //  Add the 'documentation' column in the tech_tips table
        if(!Schema::hasColumn('tech_tips', 'documentation'))
        {
            Schema::table('tech_tips', function(Blueprint $table) {
                $table->boolean('documentation')->default(0)->nullable()->after('public');
            });
            
            //  Move all of the system files over to the tech tips table
            $sysFiles = DB::select('SELECT * FROM `system_files`');
            foreach($sysFiles as $sysFile)
            {
                $newTip = TechTips::create([
                    'user_id'       => $sysFile->user_id,
                    'public'        => 0,
                    'documentation' => 1,
                    'subject'       => $sysFile->name,
                    'description'   => empty($sysFile->description) ? $sysFile->name : $sysFile->description,
                    'created_at'    => $sysFile->created_at,
                    'updated_at'    => $sysFile->updated_at
                ]);

                $tipId = $newTip->tip_id;
                TechTipFiles::create([
                    'tip_id'  => $tipId,
                    'file_id' => $sysFile->file_id
                ]);
                TechTipSystems::create([
                    'tip_id' => $tipId,
                    'sys_id' => $sysFile->sys_id
                ]);
            }
        }
        
        //  Add the 'is_installer' column in the users table
        if(!Schema::hasColumn('users', 'is_installer'))
        {
            Schema::table('users', function(Blueprint $table) {
                $table->boolean('is_installer')->default(0)->after('active');
            });
            
            //  Migrate user roles from the 'user roles' table to the new 'user permissions' table
            if(Schema::hasTable('user_permissions') && (UserPermissions::all()->isEmpty()))
            {
                $userRoles = DB::select('SELECT * FROM `user_role` LEFT JOIN `roles` ON `user_role`.`role_id` = `roles`.`role_id`');
                
                foreach($userRoles as $user)
                {
                    if($user->name === 'Installer')
                    {
                        User::find($user->user_id)->update(
                        [
                            'is_installer' => 1
                        ]);
                        UserPermissions::create(
                        [
                            'user_id'             => $user->user_id,
                            'manage_users'        => 1,
                            'run_reports'         => 1,
                            'add_customer'        => 1,
                            'deactivate_customer' => 1,
                            'use_file_links'      => 1,
                            'create_tech_tip'     => 1,
                            'edit_tech_tip'       => 1,
                            'delete_tech_tip'     => 1,
                            'create_category'     => 1,
                            'modify_category'     => 1
                        ]);
                    }
                    else if($user->name === 'Admin')
                    {
                        UserPermissions::create(
                        [
                            'user_id'             => $user->user_id,
                            'manage_users'        => 1,
                            'run_reports'         => 1,
                            'add_customer'        => 1,
                            'deactivate_customer' => 1,
                            'use_file_links'      => 1,
                            'create_tech_tip'     => 1,
                            'edit_tech_tip'       => 1,
                            'delete_tech_tip'     => 1,
                            'create_category'     => 1,
                            'modify_category'     => 1
                        ]);
                    }
                    else if($user->name === 'Report')
                    {
                        UserPermissions::create(
                        [
                            'user_id'             => $user->user_id,
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
                    else
                    {
                        UserPermissions::create(
                        [
                            'user_id'             => $user->user_id,
                            'manage_users'        => 0,
                            'run_reports'         => 0,
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
                Schema::table('user_role', function(Blueprint $table) {
                    $table->dropForeign(['user_id']);
                    $table->dropForeign(['role_id']);
                });
                Schema::dropIfExists('user_role');
                Schema::dropIfExists('roles');
            }
        }
        
        //  Remove the system_files and system_file_types table
        if(Schema::hasTable('system_files'))
        {
            Schema::table('system_files', function(Blueprint $table) {
                $table->dropForeign(['sys_id']);
                $table->dropForeign(['type_id']);
                $table->dropForeign(['file_id']);
                $table->dropForeign(['user_id']);
            });
            Schema::dropIfExists('system_files');
            Schema::dropIfExists('system_file_types');
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('file_links', 'cust_id'))
        {
            Schema::table('file_links', function(Blueprint $table) {
                $table->dropForeign(['cust_id']);
                $table->dropColumn('cust_id');
            });
        }
        
        if(!Schema::hasColumn('file_links', 'note'))
        {
            Schema::table('file_links', function(Blueprint $table) {
                $table->dropColumn('note');
            });
        }
        
        if(Schema::hasColumn('tech_tips', 'documentation'))
        {
            Schema::table('tech_tips', function(Blueprint $table) {
                $table->dropColumn('documentation');
            });
        }
    }
}
