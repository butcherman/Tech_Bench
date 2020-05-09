<?php

namespace App\Domains\Maintenance;

use Illuminate\Support\Facades\Log;

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

    //  users table contains the user_role foreign key
    protected function users()
    {
        $valid = true;
        $list = User::select('role_id')->groupBy('role_id')->get();
        foreach($list as $l)
        {
            $v = UserRoleType::find($l->role_id);
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = User::where('role_id', $l->role_id);
                    Log::notice('DBCheck - user Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        return $valid;
    }

    //  user_role_permissions table has the role_id and perm_type_id foreign keys
    protected function user_role_permissions()
    {
        $valid = true;

        //  Check role_id foreign key
        $list  = UserRolePermissions::select('role_id')->groupBy('role_id')->get();
        foreach($list as $l)
        {
            $v = UserRoleType::find($l->role_id);
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = UserRolePermissions::where('role_id', $l->role_id);
                    Log::notice('DBCheck - user_role_permissions Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check perm_type_id foreign key
        $list  = UserRolePermissions::select('perm_type_id')->groupBy('perm_type_id')->get();
        foreach($list as $l)
        {
            $v = UserRolePermissionTypes::find($l->perm_type_id);
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = UserRolePermissions::where('perm_type_id', $l->perm_type_id);
                    Log::notice('DBCheck - user_role_permissions Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        return $valid;
    }

    //  user_logins table has the user_id foreign key
    protected function user_logins()
    {
        $valid = true;

        //  Check role_id foreign key
        $list  = UserLogins::select('user_id')->groupBy('user_id')->get();
        foreach($list as $l)
        {
            $v = User::where('user_id', $l->user_id)->withTrashed()->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = UserLogins::where('user_id', $l->user_id);
                    Log::notice('DBCheck - user_logins Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        return $valid;
    }

    //  tech_tips table has user_id, updated_id and tip_type_id foreign keys
    protected function tech_tips()
    {
        $valid = true;

        //  Check user_id foreign key
        $list  = TechTips::select('user_id')->groupBy('user_id')->get();
        foreach($list as $l)
        {
            $v = User::where('user_id', $l->user_id)->withTrashed()->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $adminUser = User::orderBy('user_id')->first();
                    $tipList   = TechTips::where('user_id', $l->user_id)->get();
                    foreach($tipList as $tip)
                    {
                        Log::notice('DBCheck - tech_tip Foreign key failed.  Updating user_id column - ', array($tip));
                        $tip->update([
                            'user_id' => $adminUser->user_id,
                        ]);
                    }
                }
            }
        }

        //  Check updated_id foreign key
        $list  = TechTips::select('updated_id')->groupBy('updated_id')->get();
        foreach($list as $l)
        {
            $v = User::where('user_id', $l->user_id)->withTrashed()->get();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $adminUser = User::orderBy('user_id')->first();
                    $tipList   = TechTips::where('updated_id', $l->user_id)->get();
                    foreach($tipList as $tip)
                    {
                        Log::notice('DBCheck - tech_tip Foreign key failed.  Updating updated_id column - ', array($tip));
                        $tip->update([
                            'updated_id' => $adminUser->user_id,
                        ]);
                    }
                }
            }
        }

        //  Check tip_type_id foreign key
        $list  = TechTips::select('tip_type_id')->groupBy('tip_type_id')->get();
        foreach($list as $l)
        {
            $v = TechTipTypes::find($l->tip_type_id);
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = TechTips::where('tip_type_id', $l->tip_type_id);
                    Log::notice('DBCheck - tech_tip Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        return $valid;
    }

    //  tech_tip_systems table has tip_id and sys_id foreign keys
    protected function tech_tip_systems()
    {
        $valid = true;

        //  Check tech_tip foreign key
        $list  = TechTipSystems::select('tip_id')->groupBy('tip_id')->get();
        foreach($list as $l)
        {
            $v = TechTips::find($l->tip_id);
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = TechTipSystems::where('tip_id', $l->tip_id);
                    Log::notice('DBCheck - tech_tip_systems Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check sys_id foreign key
        $list  = TechTipSystems::select('sys_id')->groupBy('sys_id')->get();
        foreach($list as $l)
        {
            $v = SystemTypes::find($l->sys_id);
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = TechTipSystems::where('sys_id', $l->sys_id);
                    Log::notice('DBCheck - tech_tip_systems Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        return $valid;
    }

    //  tech_tip_files table has tip_id and file_id foreign keys
    protected function tech_tip_files()
    {
        $valid = true;

        //  Check tip_id foreign key
        $list  = TechTipFiles::select('tip_id')->groupBy('tip_id')->get();
        foreach($list as $l)
        {
            $v = TechTips::find($l->tip_id);
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = TechTipFiles::where('tip_id', $l->tip_id);
                    Log::notice('DBCheck - tech_tip_files Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check file_id foreign key
        $list  = TechTipFiles::select('file_id')->groupBy('file_id')->get();
        foreach($list as $l)
        {
            $v = Files::find($l->file_id);
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = TechTipFiles::where('file_id', $l->file_id);
                    Log::notice('DBCheck - tech_tip_files Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        return $valid;
    }

    //  tech_tip_favs table has the user_id and tip_id foreign keys
    protected function tech_tip_favs()
    {
        $valid = true;

        //  Check tip_id foreign key
        $list  = TechTipFavs::select('tip_id')->groupBy('tip_id')->get();
        foreach($list as $l)
        {
            $v = TechTips::find($l->tip_id);
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = TechTipFavs::where('tip_id', $l->tip_id);
                    Log::notice('DBCheck - tech_tip_favs Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check user_id foreign key
        $list  = TechTipFavs::select('user_id')->groupBy('user_id')->get();
        foreach($list as $l)
        {
            $v = User::find($l->user_id);
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = TechTipFavs::where('user_id', $l->user_id);
                    Log::notice('DBCheck - tech_tip_favs Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        return $valid;
    }

    //  tech_tip_comments table has the tip_id and user_id foreign keys
    protected function tech_tip_comments()
    {
        $valid = true;

        //  Check tip_id foreign key
        $list  = TechTipComments::select('tip_id')->groupBy('tip_id')->get();
        foreach($list as $l)
        {
            $v = TechTips::find($l->tip_id);
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = TechTipComments::where('tip_id', $l->tip_id);
                    Log::notice('DBCheck - tech_tip_comments Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check user_id foreign key
        $list  = TechTipComments::select('user_id')->groupBy('user_id')->get();
        foreach($list as $l)
        {
            $v = User::find($l->user_id);
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = TechTipComments::where('user_id', $l->user_id);
                    Log::notice('DBCheck - tech_tip_comments Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        return $valid;
    }

    //  system_types table has cat_id foreign key
    protected function system_types()
    {
        $valid = true;

        //  Check cat_id foreign key
        $list  = SystemTypes::select('cat_id')->groupBy('cat_id')->get();
        foreach($list as $l)
        {
            $v = SystemCategories::where('cat_id', $l->cat_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = SystemTypes::where('cat_id', $l->cat_id);
                    Log::notice('DBCheck - system_types Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        return $valid;
    }

    //  system_data_fields table has sys_id and data_type_id foreign keys
    protected function system_data_fields()
    {
        $valid = true;

        //  Check sys_id foreign key
        $list  = SystemDataFields::select('sys_id')->groupBy('sys_id')->get();
        foreach($list as $l)
        {
            $v = SystemTypes::where('sys_id', $l->sys_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = SystemDataFields::where('sys_id', $l->sys_id);
                    Log::notice('DBCheck - system_data_fields Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check data_type_id foreign key
        $list  = SystemDataFields::select('data_type_id')->groupBy('data_type_id')->get();
        foreach($list as $l)
        {
            $v = SystemDataFieldTypes::where('data_type_id', $l->data_type_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = SystemDataFields::where('data_type_id', $l->data_type_id);
                    Log::notice('DBCheck - system_data_fields Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        return $valid;
    }


    //  file_links table has user_id and cust_id foreign keys
    protected function file_links()
    {
        $valid = true;

        //  Check user_id foreign key
        $list  = FileLinks::select('user_id')->groupBy('user_id')->get();
        foreach($list as $l)
        {
            $v = User::where('user_id', $l->user_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = FileLinks::where('user_id', $l->user_id);
                    Log::notice('DBCheck - file_links Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check cust_id foreign key
        $list  = FileLinks::select('cust_id')->where('cust_id', '!=', null)->groupBy('cust_id')->get();
        foreach($list as $l)
        {
            $v = Customers::where('cust_id', $l->cust_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = FileLinks::where('cust_id', $l->cust_id);
                    Log::notice('DBCheck - file_links Foreign key failed.  Updating data - ', array($data));
                    foreach($data as $d)
                    {
                        $d->update([
                            'cust_id' => null,
                        ]);
                    }
                }
            }
        }

        return $valid;
    }

    //  file_link_files table has link_id, file_id, and user_id foreign keys
    protected function file_link_files()
    {
        $valid = true;

        //  Check link_id foreign key
        $list  = FileLinkFiles::select('link_id')->groupBy('link_id')->get();
        foreach($list as $l)
        {
            $v = FileLinks::where('link_id', $l->link_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = FileLinkFiles::where('link_id', $l->link_id);
                    Log::notice('DBCheck - file_link_files Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check file_id foreign key
        $list  = FileLinkFiles::select('file_id')->groupBy('file_id')->get();
        foreach($list as $l)
        {
            $v = Files::where('file_id', $l->file_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = FileLinkFiles::where('file_id', $l->file_id);
                    Log::notice('DBCheck - file_link_files Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check user_id foreign key
        $list  = FileLinkFiles::select('user_id')->groupBy('user_id')->get();
        foreach($list as $l)
        {
            if($l->user_id)
            {
                $v = User::where('user_id', $l->user_id)->withTrashed()->first();
                if(!$v)
                {
                    $valid = false;
                    if($this->fix)
                    {
                        $data = FileLinkFiles::where('user_id', $l->user_id);
                        Log::notice('DBCheck - file_link_files Foreign key failed.  Deleting data - ', array($data));
                        $data->delete();
                    }
                }
            }
        }

        return $valid;
    }

    //  customer_systems table has cust_id and sys_id foreign keys
    protected function customer_systems()
    {
        $valid = true;

        //  Check cust_id foreign key
        $list  = CustomerSystems::select('cust_id')->groupBy('cust_id')->get();
        foreach($list as $l)
        {
            $v = Customers::where('cust_id', $l->cust_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = CustomerSystems::where('cust_id', $l->cust_id);
                    Log::notice('DBCheck - customer_systems Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check sys_id foreign key
        $list  = CustomerSystems::select('sys_id')->groupBy('sys_id')->get();
        foreach($list as $l)
        {
            $v = SystemTypes::where('sys_id', $l->sys_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = CustomerSystems::where('sys_id', $l->sys_id);
                    Log::notice('DBCheck - customer_systems Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        return $valid;
    }

    //  The customer_system_data table has the cust_sys_id and field_id foreign keys
    protected function customer_system_data()
    {
        $valid = true;

        //  Check cust_sys_id foreign key
        $list  = CustomerSystemData::select('cust_sys_id')->groupBy('cust_sys_id')->get();
        foreach($list as $l)
        {
            $v = CustomerSystems::where('cust_sys_id', $l->cust_sys_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = CustomerSystemData::where('cust_sys_id', $l->cust_sys_id);
                    Log::notice('DBCheck - customer_system_data Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check field_id foreign key
        $list  = CustomerSystemData::select('field_id')->groupBy('field_id')->get();
        foreach($list as $l)
        {
            $v = SystemDataFields::where('field_id', $l->field_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = CustomerSystemData::where('field_id', $l->field_id);
                    Log::notice('DBCheck - customer_system_data Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        return $valid;
    }

    //  customer_notes table has cust_id and user_id foreign keys
    protected function customer_notes()
    {
        $valid = true;

        //  Check cust_id foreign key
        $list  = CustomerNotes::select('cust_id')->groupBy('cust_id')->get();
        foreach($list as $l)
        {
            $v = Customers::where('cust_id', $l->cust_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = CustomerNotes::where('cust_id', $l->cust_id);
                    Log::notice('DBCheck - customer_notes Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check user_id foreign key
        $list  = CustomerNotes::select('user_id')->groupBy('user_id')->get();
        foreach($list as $l)
        {
            if($l->user_id)
            {
                $v = User::where('user_id', $l->user_id)->withTrashed()->first();
                if(!$v)
                {
                    $valid = false;
                    if($this->fix)
                    {
                        $data = CustomerNotes::where('user_id', $l->user_id);
                        Log::notice('DBCheck - customer_notes Foreign key failed.  Deleting data - ', array($data));
                        $data->delete();
                    }
                }
            }
        }

        return $valid;
    }

    //  customer_files table has file_id, file_type_id, cust_id, and user_id foreign keys
    protected function customer_files()
    {
        $valid = true;

        //  Check file_id foreign key
        $list  = CustomerFiles::select('file_id')->groupBy('file_id')->get();
        foreach($list as $l)
        {
            $v = Files::where('file_id', $l->file_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = CustomerFiles::where('file_id', $l->file_id);
                    Log::notice('DBCheck - customer_files Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check file_type_id foreign key
        $list  = CustomerFiles::select('file_type_id')->groupBy('file_type_id')->get();
        foreach($list as $l)
        {
            $v = CustomerFileTypes::where('file_type_id', $l->file_type_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = CustomerFiles::where('file_type_id', $l->file_type_id);
                    Log::notice('DBCheck - customer_files Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check cust_id foreign key
        $list  = CustomerFiles::select('cust_id')->groupBy('cust_id')->get();
        foreach($list as $l)
        {
            $v = Customers::where('cust_id', $l->cust_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = CustomerFiles::where('cust_id', $l->cust_id);
                    Log::notice('DBCheck - customer_files Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check user_id foreign key
        $list  = CustomerFiles::select('user_id')->groupBy('user_id')->get();
        foreach($list as $l)
        {
            if($l->user_id)
            {
                $v = User::where('user_id', $l->user_id)->withTrashed()->first();
                if(!$v)
                {
                    $valid = false;
                    if($this->fix)
                    {
                        $data = CustomerFiles::where('user_id', $l->user_id);
                        Log::notice('DBCheck - customer_files Foreign key failed.  Deleting data - ', array($data));
                        $data->delete();
                    }
                }
            }
        }

        return $valid;
    }

    //  customer_favs table has user_id and cust_id foreign keys
    protected function customer_favs()
    {
        $valid = true;

        //  Check cust_id foreign key
        $list  = CustomerFavs::select('cust_id')->groupBy('cust_id')->get();
        foreach($list as $l)
        {
            $v = Customers::where('cust_id', $l->cust_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = CustomerFavs::where('cust_id', $l->cust_id);
                    Log::notice('DBCheck - customer_favs Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check user_id foreign key
        $list  = CustomerFavs::select('user_id')->groupBy('user_id')->get();
        foreach($list as $l)
        {
            if($l->user_id)
            {
                $v = User::where('user_id', $l->user_id)->first();
                if(!$v)
                {
                    $valid = false;
                    if($this->fix)
                    {
                        $data = CustomerFavs::where('user_id', $l->user_id);
                        Log::notice('DBCheck - customer_favs Foreign key failed.  Deleting data - ', array($data));
                        $data->delete();
                    }
                }
            }
        }

        return $valid;
    }

    //  customer_contacts table has cust_id foreign key
    protected function customer_contacts()
    {
        $valid = true;

        //  Check cust_id foreign key
        $list  = CustomerContacts::select('cust_id')->groupBy('cust_id')->get();
        foreach($list as $l)
        {
            $v = Customers::where('cust_id', $l->cust_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = CustomerContacts::where('cust_id', $l->cust_id);
                    Log::notice('DBCheck - customer_contacts Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        return $valid;
    }

    //  customer_contact_phones table has cont_id and phone_type_id table
    protected function customer_contact_phones()
    {
        $valid = true;

        //  Check cont_id foreign key
        $list  = CustomerContactPhones::select('cont_id')->groupBy('cont_id')->get();
        foreach($list as $l)
        {
            $v = CustomerContacts::where('cont_id', $l->cont_id)->first();
            if(!$v)
            {
                $valid = false;
                if($this->fix)
                {
                    $data = CustomerContactPhones::where('cont_id', $l->cont_id);
                    Log::notice('DBCheck - customer_contact_phones Foreign key failed.  Deleting data - ', array($data));
                    $data->delete();
                }
            }
        }

        //  Check phone_type_id foreign key
        $list  = CustomerContactPhones::select('phone_type_id')->groupBy('phone_type_id')->get();
        foreach($list as $l)
        {
            if($l->user_id)
            {
                $v = PhoneNumberTypes::where('phone_type_id', $l->phone_type_id)->first();
                if(!$v)
                {
                    $valid = false;
                    if($this->fix)
                    {
                        $data = CustomerContactPhones::where('phone_type_id', $l->phone_type_id);
                        Log::notice('DBCheck - customer_contact_phones Foreign key failed.  Deleting data - ', array($data));
                        $data->delete();
                    }
                }
            }
        }

        return $valid;
    }
}
