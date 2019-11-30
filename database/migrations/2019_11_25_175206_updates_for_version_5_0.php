<?php

use App\User;
use App\TechTips;
use App\Customers;
use App\FileLinks;
use App\TechTipFiles;
use App\FileLinkFiles;
use App\TechTipSystems;
use App\UserPermissions;
use App\PhoneNumberTypes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatesForVersion50 extends Migration
{
    /**
     * Database changes for version 5.0
     *
     * @return void
     */
    public function up()
    {
        //  See the function itself for a description of the database changes
        //  DB Modifications
        $this->updatePhoneIcons();
        $this->modifySystemDataTableNames();
        $this->migrateSystemDocumentation();

        //  DB Adds
        $this->addSoftDeleteToCustomerSystems();
        $this->addSoftDeleteToTechTips();
        $this->addPasswordExpiresColumn();
        $this->addIsInstallerToUsers();
        $this->addHiddenColumn();
        $this->addColumnsToFileLinksTable();
        $this->addNotesColumnToFileLinkFiles();
        // $this->addDocumentationColumnToTechTips();

        //  Remove Unneeded Tables
        $this->removeNavBarView();
        $this->removeUserRolesTables();
        $this->removeFileLinkInstructionsTable();
        $this->removeFileLinkNotesTable();
        $this->removeSystemFilesTables();
        $this->removeActiveFromCustomers();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        return 'Unable to Process.  Please Downgrade app and load backup.';
    }

    /*
    *   Database Modifications
    */
    //  Update the icon class to change from font awesome, to theymify icons
    private function updatePhoneIcons()
    {
        $newIcons = [
            ['description' => 'Home',   'icon_class' => 'ti-home'],
            ['description' => 'Work',   'icon_class' => 'ti-briefcase'],
            ['description' => 'Mobile', 'icon_class' => 'ti-mobile'],
        ];

        foreach($newIcons as $new)
        {
            PhoneNumberTypes::where('description', $new['description'])->update(['icon_class' => $new['icon_class']]);
        }
    }

    /*
    *   New Columns and Tables
    */
    //  Added the ability to set an expiration date for user passwords - will force them to change after this expires
    private function addPasswordExpiresColumn()
    {
        if(!Schema::hasColumn('users', 'password_expires')) {
            Schema::table('users', function(Blueprint $table) {
                $table->timestamp('password_expires')
                    ->nullable()
                    ->after('active');
            });
        }
    }

    //  Add the is installer column to the users table
    private function addIsInstallerToUsers()
    {
        if(!Schema::hasColumn('users', 'is_installer'))
        {
            //  Add the is installer column
            Schema::table('users', function(Blueprint $table) {
                $table->boolean('is_installer')->default(0)->after('active');
            });
            //  Make user id 1 the installer user
            User::find(1)->update(['is_installer' => 1]);
            //  default permissions to be used for remaining users
            $defaultPermissions =
            [
                'user_id'             => null,
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
            ];

            $userRoles = DB::select('SELECT * FROM `user_role` LEFT JOIN `roles` ON `user_role`.`role_id` = `roles`.`role_id`');
            $permissions = UserPermissions::all();

            //  Cycle thorugh all users and assign proper permissions
            foreach($userRoles as $user)
            {
                $userPerm = $defaultPermissions;
                if(!$permissions->contains($user->user_id))
                {
                    switch($user->name)
                    {
                        //  Fallthrough is intended, modifications will be picked up top-down
                        case 'Installer':
                            User::find($user->user_id)->update(['is_installer' => 1]);
                            /* FALLTHROUGH */
                        case 'Admin':
                            $userPerm['manage_users']        = 1;
                            $userPerm['deactivate_customer'] = 1;
                            $userPerm['edit_tech_tip']       = 1;
                            $userPerm['delete_tech_tip']     = 1;
                            $userPerm['create_category']     = 1;
                            $userPerm['modify_category']     = 1;
                            /* FALLTHROUGH */
                        case 'Report':
                            $userPerm['run_reports']         = 1;
                            /* FALLTHROUGH */
                        default:
                            $userPerm['user_id']             = $user->user_id;
                    }
                    UserPermissions::create($userPerm);
                }
            }
        }
    }

    //  Added a 'hidden' attribute to system customer data types to allow passwords to not be viewed unless clicked or focus
    private function addHiddenColumn()
    {
        if(!Schema::hasColumn('system_data_field_types', 'hidden')) {
            Schema::table('system_cust_data_types', function(Blueprint $table) {
                $table->boolean('hidden')
                    ->default(0)
                    ->after('name');
            });
        }
    }

    //  Update the File links table - add cust_id and note column
    private function addColumnsToFileLinksTable()
    {
        if(!Schema::hasColumn('file_links', 'cust_id')) {
            Schema::table('file_links', function(Blueprint $table) {
                $table->integer('cust_id')
                    ->unsigned()
                    ->nullable()
                    ->after('user_id');
                $table->foreign('cust_id')->references('cust_id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            });
        }
        if(!Schema::hasColumn('file_links', 'note')) {
            Schema::table('file_links', function(Blueprint $table) {
                $table->longText('note')
                    ->nullable()
                    ->after('link_name');
            });
            //  Migrate the instructions from the old table to the new column
            $instructions = DB::select('SELECT * FROM `file_link_instructions`');
            foreach($instructions as $ins) {
                FileLinks::find($ins->link_id)->update([
                    'note' => $ins->instruction
                ]);
            }
        }
    }

    //  Add Notes column to the file links files table
    private function addNotesColumnToFileLinkFiles()
    {
        if(!Schema::hasColumn('file_link_files', 'note')) {
            Schema::table('file_link_files', function(Blueprint $table) {
                $table->longText('note')
                    ->nullable()
                    ->after('upload');
            });
            //  Migrate the existing notes to the new table
            $notes = DB::select('SELECT * FROM `file_link_notes`');
            foreach($notes as $note) {
                FileLinkFiles::where('file_id', $note->file_id)->update([
                    'note' => $note->note
                ]);
            }
        }
    }

    //  Add the documentation column to the tech tips table
    private function migrateSystemDocumentation()
    {
        if(!Schema::hasColumn('tech_tips', 'tip_type_id')) {
            Schema::table('tech_tips', function(Blueprint $table) {
                $table->bigInteger('tip_type_id')->unsigned()->after('public')->default(1);
                $table->foreign('tip_type_id')->references('tip_type_id')->on('tech_tip_types')->onUpdate('cascade');
            });

            //  Move all of the system files over to the tech tips table
            $sysFiles = DB::select('SELECT * FROM `system_files`');
            foreach($sysFiles as $sysFile) {
                $newTip = TechTips::create([
                    'user_id'       => $sysFile->user_id,
                    'public'        => 0,
                    'tip_type_id'   => 2,
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
    }

    /*
    *   Database Cleanup
    */
    //  Remove the NavBar view if it exists - We no longer need it to sort out the system types
    private function removeNavBarView()
    {
        DB::statement('DROP VIEW IF EXISTS `navbar_view`');
    }

    //  Remove the active column from the customers table
    private function removeActiveFromCustomers()
    {
        if(Schema::hasColumn('customers', 'active'))
        {
            //  Migrate the existing disabled customers first
            $deactivatedCustomers = Customers::where('active', 0)->get();
            foreach($deactivatedCustomers as $cust)
            {
                Customers::find($cust->cust_id)->delete();
            }

            Schema::table('customers', function(Blueprint $table) {
                $table->dropColumn('active');
            });
        }
    }

    //  Remove the user roles tables
    private function removeUserRolesTables()
    {
        if(Schema::hasTable('user_role'))
        {
            Schema::table('user_role', function(Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropForeign(['role_id']);
            });
            Schema::dropIfExists('user_role');
            Schema::dropIfExists('roles');
        }
    }

    //  Remove the File Link Instructions Table
    private function removeFileLinkInstructionsTable()
    {
        if(Schema::hasTable('file_link_instructions'))
        {
            //  Remove the foreign key to allow for dropping the table
            Schema::table('file_link_instructions', function(Blueprint $table) {
                $table->dropForeign(['link_id']);
            });
            Schema::dropIfExists('file_link_instructions');
        }
    }

    //  Remove the file link files note table
    private function removeFileLinkNotesTable()
    {
        if(Schema::hasTable('file_link_notes'))
        {
            Schema::table('file_link_notes', function(Blueprint $table) {
                $table->dropForeign(['link_id']);
                $table->dropForeign(['file_id']);
            });
            Schema::dropIfExists('file_link_notes');
        }
    }

    //  Remove the system files and system file types table
    private function removeSystemFilesTables()
    {
        if(Schema::hasTable('system_files')) {
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

    //  Rename the tables that hold customer system information to make more sense
    private function modifySystemDataTableNames()
    {
        if(Schema::hasTable('customer_system_fields'))
        {
            Schema::dropIfExists('customer_system_data');
            Schema::rename('customer_system_fields', 'customer_system_data');
        }
        if(Schema::hasTable('system_cust_data_fields'))
        {
            Schema::dropIfExists('system_data_fields');
            Schema::rename('system_cust_data_fields', 'system_data_fields');
        }
        if(Schema::hasTable('system_cust_data_types'))
        {
            Schema::dropIfExists('system_data_field_types');
            Schema::rename('system_cust_data_types', 'system_data_field_types');
        }
    }

    //  Add soft deletes to customer systems table to prevent accidental deletes
    private function addSoftDeleteToCustomerSystems()
    {
        if(!Schema::hasColumn('customer_systems', 'deleted_at'))
        {
            Schema::table('customer_systems', function(Blueprint $table) {
                $table->softDeletes()->after('sys_id');
            });
        }
    }

    //  Add soft deletes to tech tips table to prevent accidental deletes
    private function addSoftDeleteToTechTips()
    {
        if (!Schema::hasColumn('tech_tips', 'deleted_at')) {
            Schema::table('tech_tips', function (Blueprint $table) {
                $table->softDeletes()->after('description');
            });
        }
    }
}
