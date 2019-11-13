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

        //  DB Adds
        $this->addPasswordExpiresColumn();
        $this->addIsInstallerToUsers();
        $this->addHiddenColumn();
        $this->addColumnsToFileLinksTable();
        $this->addNotesColumnToFileLinkFiles();
        $this->addDocumentationColumnToTechTips();

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
        PhoneNumberTypes::find(1)->update(
            [
                'icon_class' => 'ti-home'
            ]
        );
        PhoneNumberTypes::find(2)->update(
            [
                'icon_class' => 'ti-briefcase'
            ]
        );
        PhoneNumberTypes::find(3)->update(
            [
                'icon_class' => 'ti-mobile'
            ]
        );
    }

    /*
    *   New Columns and Tables
    */
    //  Added the ability to set an expiration date for user passwords - will force them to change after this expires
    private function addPasswordExpiresColumn()
    {
        if (!Schema::hasColumn('users', 'password_expires')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('password_expires')
                    ->nullable()
                    ->after('active');
            });
        }
    }

    //  Add the is installer column to the users table
    private function addIsInstallerToUsers()
    {
        if (!Schema::hasColumn('users', 'is_installer')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_installer')->default(0)->after('active');
            });

            //  Migrate user roles from the 'user roles' table to the new 'user permissions' table
            if (Schema::hasTable('user_permissions') && (UserPermissions::all()->isEmpty())) {
                $userRoles = DB::select('SELECT * FROM `user_role` LEFT JOIN `roles` ON `user_role`.`role_id` = `roles`.`role_id`');

                foreach ($userRoles as $user) {
                    if ($user->name === 'Installer') {
                        User::find($user->user_id)->update(
                            [
                                'is_installer' => 1
                            ]
                        );
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
                            ]
                        );
                    } else if ($user->name === 'Admin') {
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
                            ]
                        );
                    } else if ($user->name === 'Report') {
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
                            ]
                        );
                    } else {
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
                            ]
                        );
                    }
                }
            }
        }
    }

    //  Added a 'hidden' attribute to system customer data types to allow passwords to not be viewed unless clicked or focus
    private function addHiddenColumn()
    {
        if (!Schema::hasColumn('system_cust_data_types', 'hidden')) {
            Schema::table('system_cust_data_types', function (Blueprint $table) {
                $table->boolean('hidden')
                    ->default(0)
                    ->after('name');
            });
        }
    }

    //  Update the File links table - add cust_id and note column
    private function addColumnsToFileLinksTable()
    {
        if (!Schema::hasColumn('file_links', 'cust_id')) {
            Schema::table('file_links', function (Blueprint $table) {
                $table->integer('cust_id')
                    ->unsigned()
                    ->nullable()
                    ->after('user_id');
                $table->foreign('cust_id')->references('cust_id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('file_links', 'note')) {
            Schema::table('file_links', function (Blueprint $table) {
                $table->longText('note')
                    ->nullable()
                    ->after('link_name');
            });
            //  Migrate the instructions from the old table to the new column
            $instructions = DB::select('SELECT * FROM `file_link_instructions`');
            foreach ($instructions as $ins) {
                FileLinks::find($ins->link_id)->update([
                    'note' => $ins->instruction
                ]);
            }
        }
    }

    //  Add Notes column to the file links files table
    private function addNotesColumnToFileLinkFiles()
    {
        if (!Schema::hasColumn('file_link_files', 'note')) {
            Schema::table('file_link_files', function (Blueprint $table) {
                $table->longText('note')
                    ->nullable()
                    ->after('upload');
            });
            //  Migrate the existing notes to the new table
            $notes = DB::select('SELECT * FROM `file_link_notes`');
            foreach ($notes as $note) {
                FileLinkFiles::where('file_id', $note->file_id)->update([
                    'note' => $note->note
                ]);
            }
        }
    }

    //  Add the documentation column to the tech tips table
    private function addDocumentationColumnToTechTips()
    {
        if (!Schema::hasColumn('tech_tips', 'documentation')) {
            Schema::table('tech_tips', function (Blueprint $table) {
                $table->boolean('documentation')->default(0)->nullable()->after('public');
            });

            //  Move all of the system files over to the tech tips table
            $sysFiles = DB::select('SELECT * FROM `system_files`');
            foreach ($sysFiles as $sysFile) {
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
        if (Schema::hasColumn('customers', 'active'))
        {
            //  Migrate the existing disabled customers first
            $deactiveatedCustomers = Customers::where('active', 0);
            foreach($deactiveatedCustomers as $cust)
            {
                //  TODO:  why is this not firing????
                Customers::find($cust->cust_id)->delete();
            }

            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('active');
            });
        }
    }

    //  Remove the user roles tables
    private function removeUserRolesTables()
    {
        if(Schema::hasTable('user_role'))
        {
            Schema::table('user_role', function (Blueprint $table) {
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
            Schema::table('file_link_instructions', function (Blueprint $table) {
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
            Schema::table('file_link_notes', function (Blueprint $table) {
                $table->dropForeign(['link_id']);
                $table->dropForeign(['file_id']);
            });
            Schema::dropIfExists('file_link_notes');
        }
    }

    //  Remove the system files and system file types table
    private function removeSystemFilesTables()
    {
        if (Schema::hasTable('system_files')) {
            Schema::table('system_files', function (Blueprint $table) {
                $table->dropForeign(['sys_id']);
                $table->dropForeign(['type_id']);
                $table->dropForeign(['file_id']);
                $table->dropForeign(['user_id']);
            });
            Schema::dropIfExists('system_files');
            Schema::dropIfExists('system_file_types');
        }
    }
}
