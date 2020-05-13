<?php

namespace App\Domains\Maintenance;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


use App\User;
use App\Files;
use App\TechTips;
use App\FileLinks;
use App\Customers;
use App\UserLogins;
use App\SystemTypes;
use App\TechTipFavs;
use App\CustomerFavs;
use App\UserRoleType;
use App\TechTipFiles;
use App\TechTipTypes;
use App\FileLinkFiles;
use App\CustomerFiles;
use App\CustomerNotes;
use App\TechTipSystems;
use App\TechTipComments;
use App\CustomerSystems;
use App\CustomerContacts;
use App\PhoneNumberTypes;
use App\SystemCategories;
use App\SystemDataFields;
use App\CustomerFileTypes;
use App\CustomerSystemData;
use App\UserRolePermissions;
use App\SystemDataFieldTypes;
use App\CustomerContactPhones;
use App\UserRolePermissionTypes;

class DatabaseCheck
{
    protected $fix;

    public function __construct($fix = false)
    {
        $this->fix = $fix;
    }

    public function execute($table)
    {
        return $this->$table();
    }

    protected function checkForeign($table, $fTable, $fKey)
    {
        $valid = true;
        $list = DB::select('SELECT '.$fKey.' FROM '.$table.' GROUP BY '.$fKey);
        foreach($list as $l)
        {
            if($l->$fKey != null)
            {
                $v = DB::select('SELECT * FROM '.$fTable.' WHERE '.$fKey.' = '.$l->$fKey);
                if(!$v)
                {
                    $valid = false;
                    if($this->fix)
                    {
                        $data = DB::select('SELECT * FROM '.$table.' WHERE '.$fKey.' = '.$l->$fKey);
                        Log::notice('DBCheck - '.$table.' Foreign key failed.  Deleting data - ', array($data));
                        DB::delete('delete from '.$table.' where '.$fKey.' = '.$l->$fKey);
                    }
                }
            }
        }

        return $valid;
    }

    //  users table contains the user_role foreign key
    protected function users()
    {
        return $this->checkForeign('users', 'user_role_types', 'role_id');
    }

    //  user_role_permissions table has the role_id and perm_type_id foreign keys
    protected function user_role_permissions()
    {
        $valid = true;
        $table = 'user_role_permissions';

        $keyArr = [
            'user_role_types'            => 'role_id',
            'user_role_permission_types' => 'perm_type_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }

    //  user_logins table has the user_id foreign key
    protected function user_logins()
    {
        return $this->checkForeign('user_logins', 'users', 'user_id');
    }

    //  tech_tips table has user_id, updated_id and tip_type_id foreign keys
    protected function tech_tips()
    {
        $valid = true;
        $table = 'tech_tips';

        $keyArr = [
            'users' => 'user_id',
            // 'updated_id' => 'user_id'
            //  TODO - check updated_id key
            'tech_tip_types' => 'tip_type_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }

    //  tech_tip_systems table has tip_id and sys_id foreign keys
    protected function tech_tip_systems()
    {
        $valid = true;
        $table = 'tech_tip_systems';

        $keyArr = [
            'tech_tips'    => 'tip_id',
            'system_types' => 'sys_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }

    //  tech_tip_files table has tip_id and file_id foreign keys
    protected function tech_tip_files()
    {
        $valid = true;
        $table = 'tech_tip_files';

        $keyArr = [
            'tech_tips' => 'tip_id',
            'files'     => 'file_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }

    //  tech_tip_favs table has the user_id and tip_id foreign keys
    protected function tech_tip_favs()
    {
        $valid = true;
        $table = 'tech_tip_favs';

        $keyArr = [
            'users'     => 'user_id',
            'tech_tips' => 'tip_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }

    //  tech_tip_comments table has the tip_id and user_id foreign keys
    protected function tech_tip_comments()
    {
        $valid = true;
        $table = 'tech_tip_comments';

        $keyArr = [
            'tech_tips' => 'tip_id',
            'users'     => 'user_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }

    //  system_types table has cat_id foreign key
    protected function system_types()
    {
        return $this->checkForeign('system_types', 'system_categories', 'cat_id');
    }

    //  system_data_fields table has sys_id and data_type_id foreign keys
    protected function system_data_fields()
    {
        $valid = true;
        $table = 'system_data_fields';

        $keyArr = [
            'system_types'            => 'sys_id',
            'system_data_field_types' => 'data_type_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }


    //  file_links table has user_id and cust_id foreign keys
    protected function file_links()
    {
        $valid = true;
        $table = 'file_links';

        $keyArr = [
            'users'     => 'user_id',
            'customers' => 'cust_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }

    //  file_link_files table has link_id, file_id, and user_id foreign keys
    protected function file_link_files()
    {
        $valid = true;
        $table = 'file_link_files';

        $keyArr = [
            'file_links' => 'link_id',
            'files'      => 'file_id',
            'users'      => 'user_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }

    //  customer_systems table has cust_id and sys_id foreign keys
    protected function customer_systems()
    {
        $valid = true;
        $table = 'customer_systems';

        $keyArr = [
            'customers'    => 'cust_id',
            'system_types' => 'sys_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }

    //  The customer_system_data table has the cust_sys_id and field_id foreign keys
    protected function customer_system_data()
    {
        $valid = true;
        $table = 'customer_system_data';

        $keyArr = [
            'customer_systems'   => 'cust_sys_id',
            'system_data_fields' => 'field_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }

    //  customer_notes table has cust_id and user_id foreign keys
    protected function customer_notes()
    {
        $valid = true;
        $table = 'customer_notes';

        $keyArr = [
            'customers' => 'cust_id',
            'users'     => 'user_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }

    //  customer_files table has file_id, file_type_id, cust_id, and user_id foreign keys
    protected function customer_files()
    {
        $valid = true;
        $table = 'customer_files';

        $keyArr = [
            'files'               => 'file_id',
            'customer_file_types' => 'file_type_id',
            'customers'           => 'cust_id',
            'users'               => 'user_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }

    //  customer_favs table has user_id and cust_id foreign keys
    protected function customer_favs()
    {
        $valid = true;
        $table = 'customer_favs';

        $keyArr = [
            'users'     => 'user_id',
            'customers' => 'cust_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }

    //  customer_contacts table has cust_id foreign key
    protected function customer_contacts()
    {
        return $this->checkForeign('customer_contacts', 'customers', 'cust_id');
    }

    //  customer_contact_phones table has cont_id and phone_type_id table
    protected function customer_contact_phones()
    {
        $valid = true;
        $table = 'customer_contact_phones';

        $keyArr = [
            'customer_contacts'  => 'cont_id',
            'phone_number_types' => 'phone_type_id',
        ];

        foreach($keyArr as $fTable => $key)
        {
            if(!$this->checkForeign($table, $fTable, $key))
            {
                $valid = false;
            }
        }

        return $valid;
    }
}
