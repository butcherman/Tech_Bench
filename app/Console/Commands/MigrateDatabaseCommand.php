<?php

namespace App\Console\Commands;

use Exception;
use App\Models\CustomerFile;
use App\Models\TechTipFile;
use Illuminate\Console\Command;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

use App\Traits\FileTrait;
use Illuminate\Support\Facades\Storage;

class MigrateDatabaseCommand extends Command
{
    use FileTrait;

    protected $signature   = 'tb_database:migrate {--y|yes}';
    protected $description = 'Because of the massive changes from version 5 to 6, this script is needed to properly update the database';

    protected $database;
    protected $latestVersion;

    //  All of the tables in the Version 5 database
    protected $ver5Tables = [
        'customer_contact_phones', 'customer_contacts', 'customer_favs', 'customer_file_types', 'customer_files', 'customer_notes', 'customer_system_data', 'customer_systems', 'customers',
        'file_link_files', 'file_links', 'files',
        'phone_number_types',
        'settings',
        'system_categories', 'system_data_field_types', 'system_data_fields', 'system_types',
        'tech_tip_comments', 'tech_tip_favs', 'tech_tip_files', 'tech_tip_systems', 'tech_tip_types', 'tech_tips',
        'user_initializes', 'user_logins', 'user_role_permission_types', 'user_role_permissions', 'user_role_types', 'user_settings', 'users',
    ];

    /**
     * Create a new command instance
     */
    public function __construct()
    {
        parent::__construct();

        $this->database      = config('database.connections.mysql.database');
    }

    /**
     * Execute the console command
     */
    public function handle()
    {
        $this->line('---------------------------------------------------------------');
        $this->line('|                                                              |');
        $this->line('|               Database Migration Utility                     |');
        $this->line('|                                                              |');
        $this->line('---------------------------------------------------------------');

        //  Make sure that this process is actually needed
        if(!$this->isMigrationNecessary())
        {
            $this->newLine();
            $this->info('Your Database is at the proper version');
            $this->info('Run `php artisan migrate` to update the tables');
            $this->newLine();
            return 0;
        }

        //  Using the --yes option will bypass the confirmation message
        if(!$this->option('yes'))
        {
            $this->error('-------------------------------------------------------------');
            $this->error('| VERY IMPORTANT:  PLEASE BACKUP ALL DATA BEFORE PROCEEDING |');
            $this->error('|            POSSIBLE DATA LOSS MAY OCCUR                   |');
            $this->error('-------------------------------------------------------------');

            if(!$this->confirm('Are you sure you want to continue?'))
            {
                $this->line('Exiting');
                return 0;
            }
        }

        //  Check if the _old database already exists
        $database = DB::select('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "'.$this->database.'_old"');
        if(count($database) > 0)
        {
            $this->error('ERROR - The MySQL Database already has a database named '.$this->database.'_old');
            $this->error('You must manually delete this database in order to continue');
            return 0;
        }

        //  Check to make sure we can create a database and write to it
        try
        {
            DB::statement('CREATE SCHEMA `'.$this->database.'_old`');
        }
        catch(Exception $e)
        {
            $this->error('Unable to create temporary database');
            $this->error('Please check that database user has permissions to create a new Schema');
            $this->newLine();
            $this->error('Message - '.$e);
        }

        $this->info('Moving data to a temporary database');
        $this->moveOldData();

        //  Create a default database
        $this->info('Building Default Database');
        Artisan::call('migrate:fresh');
        $this->newLine();

        //  Migrate Data
        $this->migrateAppSettings();
        $this->migrateUserRoles();
        $this->migrateUsers();
        $this->migrateEquipment();
        $this->migrateFiles();
        $this->migrateCustomers();
        $this->migrateTechTips();
        $this->fileCleanup();
        $this->checkFileLinkModule();
        $this->cleanup();

        $this->line('---------------------------------------------------------------');
        $this->line('|                                                              |');
        $this->line('|               Database Migration Complete                    |');
        $this->line('|                                                              |');
        $this->line('---------------------------------------------------------------');

        return 0;
    }

    /**
     * Determine if the database needs to be migrated
     */
    protected function isMigrationNecessary()
    {
        if(!Schema::hasTable('app_settings'))
        {
            return true;
        }

        return false;
    }

    /**
     * Move all existing information over to the temporary database
     */
    protected function moveOldData()
    {
        foreach($this->ver5Tables as $table)
        {
            try
            {
                $this->line('Moving '.$this->database.'.'.$table);
                DB::statement('RENAME TABLE `'.$this->database.'`.`'.$table.'` TO `'.$this->database.'_old`.`'.$table.'`');
            }
            catch(Exception $e)
            {
                $this->line('Table '.$this->database.'.'.$table.' does not exist');
            }
        }

        DB::statement('DROP VIEW IF EXISTS `customer_contacts_view`');
        DB::statement('DROP VIEW IF EXISTS `navbar_view`');

        $this->newLine();
    }

    /**
     * Migrate App Settings
     */
    protected function migrateAppSettings()
    {
        $this->info('Migrating App Settings');

        $current = DB::select('SELECT * FROM `'.$this->database.'_old`.`settings`');

        foreach($current as $cur)
        {
            $this->line('Adding Key - '.$cur->key);
            DB::table('app_settings')->insert([
                'key'        => $cur->key,
                'value'      => $cur->value,
                'created_at' => $cur->created_at,
                'updated_at' => NOW(),
            ]);
        }

        $this->newLine();
    }

    /**
     * Migrate User Roles
     */
    protected function migrateUserRoles()
    {
        //  Determine if there are any roles other than the default roles
        $current = DB::select('SELECT * FROM `'.$this->database.'_old`.`user_role_types`');
        if(count($current) == 4)
        {
            return false;
        }

        $this->info('Migrating User Roles');
        $roles = DB::select('SELECT * FROM `'.$this->database.'_old`.`user_role_types` WHERE `role_id` > 4');
        foreach($roles as $role)
        {
            $this->line('Creating '.$role->name.' Role');
            //  Create the Role
            DB::table('user_roles')->insert([
                'role_id'     => $role->role_id,
                'name'        => $role->name,
                'description' => $role->description,
                'allow_edit'  => 1,
                'created_at'  => $role->created_at,
                'updated_at'  => NOW()]
            );

            //  Create the permissions
            $oldPermissions = DB::select('SELECT * FROM `'.$this->database.'_old`.`user_role_permissions` WHERE `role_id` ='.$role->role_id);
            $newPermissions = [
                1  => 0,
                2  => $this->getPermissionValue($oldPermissions, 1),
                3  => $this->getPermissionValue($oldPermissions, 2),
                4  => $this->getPermissionValue($oldPermissions, 3),
                5  => $this->getPermissionValue($oldPermissions, 11),
                6  => $this->getPermissionValue($oldPermissions, 5),
                7  => $this->getPermissionValue($oldPermissions, 4),
                8  => $this->getPermissionValue($oldPermissions, 4),
                9  => $this->getPermissionValue($oldPermissions, 6),
                10 => 0,
                11 => $this->getPermissionValue($oldPermissions, 4),
                12 => $this->getPermissionValue($oldPermissions, 4),
                13 => $this->getPermissionValue($oldPermissions, 4),
                14 => $this->getPermissionValue($oldPermissions, 4),
                15 => $this->getPermissionValue($oldPermissions, 4),
                16 => $this->getPermissionValue($oldPermissions, 4),
                17 => $this->getPermissionValue($oldPermissions, 4),
                18 => $this->getPermissionValue($oldPermissions, 4),
                19 => $this->getPermissionValue($oldPermissions, 4),
                20 => $this->getPermissionValue($oldPermissions, 4),
                21 => $this->getPermissionValue($oldPermissions, 4),
                22 => $this->getPermissionValue($oldPermissions, 4),
                23 => $this->getPermissionValue($oldPermissions, 8),
                24 => $this->getPermissionValue($oldPermissions, 9),
                25 => $this->getPermissionValue($oldPermissions, 10),
                26 => $this->getPermissionValue($oldPermissions, 9),
                27 => 1,
            ];

            foreach($newPermissions as $key => $value)
            {
                DB::table('user_role_permissions')->insert([
                    'role_id'      => $role->role_id,
                    'perm_type_id' => $key,
                    'allow'        => $value,
                    'created_at'   => $oldPermissions[0]->created_at,
                    'updated_at'   => NOW(),
                ]);
            }
        }

        $this->newLine();
    }

    /**
     * Get the allow or deny of a User Role Permission Type
     */
    protected function getPermissionValue($permArray, $id)
    {
        $obj = Arr::first($permArray, function($value, $key) use ($id)
                {
                    return $value->perm_type_id == $id;
                });

        return $obj->allow;
    }

    /**
     * Migrate all users from old database
     */
    protected function migrateUsers()
    {
        $this->info('Migrating Users');

        $userList = DB::select('SELECT * FROM `'.$this->database.'_old`.`users`');
        foreach($userList as $user)
        {
            if($user->user_id === 1)
            {
                DB::table('users')->where('user_id', 1)->update((array) $user);
            }
            else
            {
                $this->line('Adding User '.$user->first_name.' '.$user->last_name);
                DB::table('users')->insert((array) $user);

                //  Migrate the users settings
                $settings = DB::select('SELECT * FROM `'.$this->database.'_old`.`user_settings` WHERE `user_id` = '.$user->user_id);
                $newSettings = [
                    1 => $settings[0]->em_notification,
                    2 => $settings[0]->em_notification,
                ];

                foreach($newSettings as $key => $value)
                {
                    DB::table('user_settings')->insert([
                        'user_id'         => $user->user_id,
                        'setting_type_id' => $key,
                        'value'           => $value,
                        'created_at'      => $user->created_at,
                        'updated_at'      => NOW(),
                    ]);
                }
            }
        }

        //  Migrate the User Logins table
        $this->line('Updated User Login Table');
        $data = DB::select('SELECT * FROM `'.$this->database.'_old`.`user_logins`');
        foreach($data as $d)
        {
            DB::table('user_logins')->insert((array) $d);
        }

        $this->newLine();
    }

    /**
     * Migrate all equipment types
     */
    protected function migrateEquipment()
    {
        $this->info('Migrating Equipment');

        //  Migrate Equipment Categories
        $cat = DB::select('SELECT * FROM `'.$this->database.'_old`.`system_categories`');
        foreach($cat as $c)
        {
            $this->line('Adding Category - '.$c->name);
            DB::table('equipment_categories')->insert((array) $c);
        }

        //  Migrate Equipment Types
        $equip = DB::select('SELECT * FROM `'.$this->database.'_old`.`system_types`');
        foreach($equip as $e)
        {
            $this->line('Adding Equipment - '.$e->name);
            DB::table('equipment_types')->insert([
                'equip_id'   => $e->sys_id,
                'cat_id'     => $e->cat_id,
                'name'       => $e->name,
                'created_at' => $e->created_at,
                'updated_at' => $e->updated_at,
            ]);
        }

        //  Migrate Data Field Types
        DB::statement('DELETE FROM `data_field_types` WHERE `name` IS NOT NULL');
        $types = DB::select('SELECT * FROM `'.$this->database.'_old`.`system_data_field_types`');
        foreach($types as $t)
        {
            $this->line('Adding Equipment Data Type - '.$t->name);
            DB::table('data_field_types')->insert([
                'type_id'    => $t->data_type_id,
                'name'       => $t->name,
                'hidden'     => $t->hidden,
                'created_at' => $t->created_at,
                'updated_at' => $t->updated_at
            ]);
        }

        $this->line('Adding Data Types to Equipment');
        $fields = DB::select('SELECT * FROM `'.$this->database.'_old`.`system_data_fields`');
        foreach($fields as $f)
        {
            DB::table('data_fields')->insert([
                'field_id'   => $f->field_id,
                'equip_id'   => $f->sys_id,
                'type_id'    => $f->data_type_id,
                'order'      => $f->order,
                'created_at' => $f->created_at,
                'updated_at' => $f->updated_at,
            ]);
        }

        $this->newLine();
    }

    /**
     * Migrate all files into the new database
     */
    protected function migrateFiles()
    {
        $this->info('Migrating Files');
        $fileList = DB::select('SELECT * FROM `'.$this->database.'_old`.`files`');
        foreach($fileList as $file)
        {
            $this->line('Adding File '.$file->file_name);
            DB::table('file_uploads')->insert([
                'file_id'    => $file->file_id,
                'disk'       => 'local',
                'folder'     => $file->file_link,
                'file_name'  => $file->file_name,
                'public'     => 0,
                'created_at' => $file->created_at,
                'updated_at' => $file->updated_at,
            ]);
        }

        $this->newLine();
    }

    /**
     * Migrate all customer information
     */
    protected function migrateCustomers()
    {
        $this->info('Migrating Customers');

        //  Migrate the primary customer information
        $customers = DB::select('SELECT * FROM `'.$this->database.'_old`.`customers`');

        //  Temporarily disable all foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach($customers as $cust)
        {
            $this->line('Adding Customer '.$cust->name);
            DB::table('customers')->insert([
                'cust_id'    => $cust->cust_id,
                'parent_id'  => $cust->parent_id,
                'name'       => $cust->name,
                'dba_name'   => $cust->dba_name,
                'slug'       => STR::slug($cust->name),
                'address'    => $cust->address,
                'city'       => $cust->city,
                'state'      => $cust->state,
                'zip'        => $cust->zip,
                'deleted_at' => $cust->deleted_at,
                'created_at' => $cust->created_at,
                'updated_at' => $cust->updated_at,
            ]);
        }

        //  Re-enable all foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->newLine();

        //  Migrate customer equipment
        $this->line('Adding Customer Equipment');
        $oldEquip = DB::select('SELECT * FROM `'.$this->database.'_old`.`customer_systems`');
        foreach($oldEquip as $equip)
        {
            DB::table('customer_equipment')->insert([
                'cust_equip_id' => $equip->cust_sys_id,
                'cust_id'       => $equip->cust_id,
                'equip_id'      => $equip->sys_id,
                'shared'        => $equip->shared,
                'deleted_at'    => $equip->deleted_at,
                'created_at'    => $equip->created_at,
                'updated_at'    => $equip->updated_at,
            ]);
        }

        //  Migrate customer equipment data
        $this->line('Adding Customer Equipment Data');
        $oldData = DB::select('SELECT * FROM `'.$this->database.'_old`.`customer_system_data`');
        foreach($oldData as $data)
        {
            DB::table('customer_equipment_data')->insert([
                'cust_equip_id' => $data->cust_sys_id,
                'field_id'      => $data->field_id,
                'value'         => $data->value,
                'created_at'    => $data->created_at,
                'updated_at'    => $data->updated_at,
            ]);
        }

        //  Migrate Customer Contacts
        $this->line('Adding Customer Contacts');
        $oldCont = DB::select('SELECT * FROM `'.$this->database.'_old`.`customer_contacts`');
        foreach($oldCont as $cont)
        {
            DB::table('customer_contacts')->insert((array) $cont);
        }

        //  Migrate Customer Contact Phone Numbers
        $oldPhone = DB::select('SELECT * FROM `'.$this->database.'_old`.`customer_contact_phones`');
        foreach($oldPhone as $phone)
        {
            DB::table('customer_contact_phones')->insert((array) $phone);
        }

        //  Migrate Customer File Types
        $this->line('Adding Customer Files');
        DB::statement('DELETE FROM `customer_file_types` WHERE `description` IS NOT NULL');
        $fileTypes = DB::select('SELECT * FROM `'.$this->database.'_old`.`customer_file_types`');
        foreach($fileTypes as $type)
        {
            DB::table('customer_file_types')->insert((array) $type);
        }

        //  Migrate Customer Files
        $oldFiles = DB::select('SELECT * FROM `'.$this->database.'_old`.`customer_files`');
        foreach($oldFiles as $file)
        {
            DB::table('customer_files')->insert((array) $file);
        }

        //  Migrate Customer Notes
        $this->line('Adding Customer Notes');
        $oldNotes = DB::select('SELECT * FROM `'.$this->database.'_old`.`customer_notes`');
        foreach($oldNotes as $note)
        {
            DB::table('customer_notes')->insert([
                'note_id'    => $note->note_id,
                'cust_id'    => $note->cust_id,
                'created_by' => $note->user_id,
                'updated_by' => null,
                'urgent'     => $note->urgent,
                'shared'     => $note->shared,
                'subject'    => $note->subject,
                'details'    => $note->description,
                'deleted_at' => null,
                'created_at' => $note->created_at,
                'updated_at' => $note->updated_at,
            ]);
        }

        //  Migrate Customer Bookmarks
        $this->line('Adding Customer Bookmarks');
        $oldBookmarks = DB::select('SELECT * FROM `'.$this->database.'_old`.`customer_favs`');
        foreach($oldBookmarks as $b)
        {
            DB::table('user_customer_bookmarks')->insert((array) $b);
        }

        $this->newLine();
    }

    /**
     * Migrate all Tech Tip data
     */
    protected function migrateTechTips()
    {
        $this->info('Migrating Tech Tips');

        //  Add the Tech Tip Types
        DB::statement('DELETE FROM `tech_tip_types` WHERE `description` IS NOT NULL');
        $oldTypes = DB::select('SELECT * FROM `'.$this->database.'_old`.`tech_tip_types`');
        foreach($oldTypes as $type)
        {
            DB::table('tech_tip_types')->insert((array) $type);
        }

        $oldTips = DB::select('SELECT * FROM `'.$this->database.'_old`.`tech_tips`');
        foreach($oldTips as $tip)
        {
            $this->line('Adding Tech Tip '.$tip->subject);
            DB::table('tech_tips')->insert([
                'tip_id'      => $tip->tip_id,
                'user_id'     => $tip->user_id,
                'updated_id'  => isset($tip->updated_id) ? $tip->updated_id : null,
                'tip_type_id' => $tip->tip_type_id,
                'sticky'      => isset($tip->sticky) ? $tip->sticky : false,
                'subject'     => $tip->subject,
                'slug'        => Str::slug($tip->subject),
                'details'     => str_replace('<img ', '<img class="img-fluid" ', $tip->description),
                'deleted_at'  => $tip->deleted_at,
                'created_at'  => $tip->created_at,
                'updated_at'  => $tip->updated_at,
            ]);
        }

        //  Add Tech Tip Equipment
        $this->newLine();
        $this->line('Adding Tech Tip Equipment');
        $oldEquipment = DB::select('SELECT * FROM `'.$this->database.'_old`.`tech_tip_systems`');
        foreach($oldEquipment as $equip)
        {
            DB::table('tech_tip_equipment')->insert([
                'tip_equip_id' => $equip->tip_tag_id,
                'tip_id'       => $equip->tip_id,
                'equip_id'     => $equip->sys_id,
            ]);
        }

        //  Add Tech Tip Files
        $this->line('Adding Tech Tip Files');
        $oldFiles = DB::select('SELECT * FROM `'.$this->database.'_old`.`tech_tip_files`');
        foreach($oldFiles as $file)
        {
            DB::table('tech_tip_files')->insert((array) $file);
        }

        //  Add Tech Tip Comments
        $this->line('Adding Tech Tip Comments');
        $oldComments = DB::select('SELECT * FROM `'.$this->database.'_old`.`tech_tip_comments`');
        foreach($oldComments as $com)
        {
            DB::table('tech_tip_comments')->insert([
                'id'         => $com->comment_id,
                'tip_id'     => $com->tip_id,
                'user_id'    => $com->user_id,
                'comment'    => $com->comment,
                'created_at' => $com->created_at,
                'updated_at' => $com->updated_at,
            ]);
        }

        //  Add Tech Tip Bookmarks
        $this->line('Adding Tech Tip Bookmarks');
        $oldBookmarks = DB::select('SELECT * FROM `'.$this->database.'_old`.`tech_tip_favs`');
        foreach($oldBookmarks as $b)
        {
            DB::table('user_tech_tip_bookmarks')->insert((array) $b);
        }

        $this->newLine();
    }

    /**
     * Cleanup files and move them into the proper folders
     */
    protected function fileCleanup()
    {
        $this->info('Cleaning up Filesystem');

        $customerFiles = CustomerFile::all();
        foreach($customerFiles as $file)
        {
            if(!$this->moveStoredFile($file->file_id, $file->cust_id, 'customers'))
            {
                $this->error('FILE MISSING ON DISK '.$file->disk.' - '.$file->FileUpload->folder.DIRECTORY_SEPARATOR.$file->FileUpload->file_name);
            }
        }

        $techTipFiles = TechTipFile::all();
        foreach($techTipFiles as $file)
        {
            if(!$this->moveStoredFile($file->file_id, $file->cust_id, 'tips'))
            {
                $this->error('FILE MISSING ON DISK '.$file->disk.' - '.$file->FileUpload->folder.DIRECTORY_SEPARATOR.$file->FileUpload->file_name);
            }
        }

        $this->newLine();
    }

    /**
     * Check if the File Link Add-on is installed,
     * If it is, migrate those tables
     */
    protected function checkFileLinkModule()
    {
        $this->info('Checking for File Link Module');

        if(!Storage::disk('modules')->exists('FileLinkModule/README.md'))
        {
            $this->line('File Link module not installed');
            $this->line('Continuing...');
            $this->newLine();

            return false;
        }

        $this->line('Found File Link Module');
        Artisan::call('tb_module:activate FileLinkModule -q');

        $oldFileLinks = DB::select('SELECT * FROM `'.$this->database.'_old`.`file_links`');
        foreach($oldFileLinks as $link)
        {
            DB::table('file_links')->insert([
                'link_id'      => $link->link_id,
                'user_id'      => $link->user_id,
                'link_hash'    => $link->link_hash,
                'link_name'    => $link->link_name,
                'expire'       => $link->expire,
                'instructions' => $link->note,
                'allow_upload' => $link->allow_upload,
                'created_at'   => $link->created_at,
                'updated_at'   => $link->updated_at,
            ]);
        }

        $oldFiles = DB::select('SELECT * FROM `'.$this->database.'_old`.`file_link_files`');
        foreach($oldFiles as $file)
        {
            DB::table('file_link_files')->insert((array) $file);
        }

        $this->newLine();
    }

    /**
     * Cleanup
     */
    protected function cleanup()
    {
        $this->info('Cleaning Up');

        DB::statement('DROP SCHEMA `'.$this->database.'_old`');
    }
}
