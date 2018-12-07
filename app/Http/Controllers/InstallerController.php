<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\SystemCustDataFields;
use App\SystemCustDataTypes;
use App\SystemCategories;
use App\SystemTypes;
use App\Settings;
use App\User;
use App\Mail\TestEmail;

class InstallerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Home page for Installer Functions
    public function index()
    {
        //  Get the list of system categories
        $cats = SystemCategories::all();
        $sysArr = [];
        //  Populate that list with the matching systems
        foreach($cats as $cat)
        {
            $systems = SystemTypes::where('cat_id', $cat->cat_id)->get();
            if(!$systems->isEmpty())
            {
                foreach($systems as $sys)
                {
                    $sysArr[$cat->name][] = $sys->name;
                }
            }
            else
            {
                $sysArr[$cat->name] = null;
            }
        }
        
        return view('installer.index', [
            'sysArr' => $sysArr
        ]); 
    }
    
    //  Server customization form
    public function customizeSystem()
    {
        return view('installer.form.customize');
    }
    
    //  Submit the server customization form
    public function submitCustom(Request $request)
    {
        $request->validate([
            'timezone' => 'required'
        ]);
        
        Settings::where('key', 'app.timezone')->update([
            'value' => $request->timezone
        ]);
        
        Log::info('Tech Bench Settings Updated', ['user_id' => Auth::user()->user_id]);
        
        return redirect()->back()->with('success', 'Tech Bench Successfully Updated');//
    }
    
    //  Email settings form
    public function emailSettings()
    {
        return view('installer.form.email');
    }
    
    //  Send a test email
    public function sendTestEmail(Request $request)
    {
        //  Make sure that all of the information properly validates
        $request->validate([
            'host'       => 'required',
            'port'       => 'required|numeric',
            'encryption' => 'required',
            'username'   => 'required'
        ]);
        
        //  Temporarily set the email settings
        config([
            'mail.host'       => $request->host,
            'mail.port'       => $request->port,
            'mail.encryption' => $request->encryption,
            'mail.username'   => $request->username,
        ]);
        
        if(!empty($request->password))
        {
            config(['mail.password' => $request->password]);
        }
        
        //  Try and send the test email
        try
        {
            Log::info('Test Email Successfully Sent to '.Auth::user()->email);
            Mail::to(Auth::user()->email)->send(new TestEmail());
            return 'success';
        }
        catch(Exception $e)
        {
            Log::notice('Test Email Failed.  Message: '.$e);
            $msg = '['.$e->getCode().'] "'.$e->getMessage().'" on line '.$e->getTrace()[0]['line'].' of file '.$e->getTrace()[0]['file'];
            return $msg;
        }
    }
    
    //  Submit the email settings form
    public function submitEmailSettings(Request $request)
    {
        $request->validate([
            'host'       => 'required',
            'port'       => 'required|numeric',
            'encryption' => 'required',
            'username'   => 'required'
        ]);
        
        //  Update each setting
        Settings::where('key', 'mail.host')->update(['value' => $request->host]);
        Settings::where('key', 'mail.port')->update(['value' => $request->port]);
        Settings::where('key', 'mail.encryption')->update(['value' => $request->encryption]);
        Settings::where('key', 'mail.username')->update(['value' => $request->username]);
        if(!empty($request->password))
        {
            Settings::where('key', 'mail.password')->update(['value' => $request->password]);
        }
            
        return redirect()->back()->with('success', 'Tech Bench Successfully Updated');//
    }
    
    //  User settings form
    public function userSettings()
    {
        $passExpire = config('users.passExpires') != null ? config('users.passExpires') : 0;
        
        return view('installer.form.users', [
            'passExpire' => $passExpire
        ]);
    }
    
    //  Submit the user settings form
    public function submitUserSettings(Request $request)
    {
        $request->validate([
            'passExpire' => 'required|numeric'
        ]);
        
        //  Determine if the password expires field is updated
        $oldExpire = config('users.passExpires');
        if($request->passExpire != $oldExpire)
        {
            //  Update the setting in the database
            Settings::where('key', 'users.passExpires')->update([
                'value' => $request->passExpire
            ]);
            //  If the setting is changing from never to xx days, update all users
            if($request->passExpire == 0)
            {
                User::whereNotNull('password_expires')->update([
                    'password_expires' => null
                ]);
            }
            else
            {
                $newExpire = Carbon::now()->addDays($request->passExpire);
                User::whereNull('password_expires')->update([
                    'password_expires' => $newExpire
                ]);
            }
        }
        
        return redirect()->back()->with('success', 'User Settings Updated');
    }
    
    //  Form to create a new Category
    public function newCat()
    {
        return view('installer.form.newCat');
    }
    
    //  Submit the new Category
    public function submitCat(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:system_categories|regex:/^[a-zA-Z0-9_ ]*$/'
        ]);
        
        $cat = SystemCategories::create([
            'name' => $request->name
        ]);
        
        Log::info('New System Category Created', ['cat_name' => $request->name, 'user_id' => Auth::user()->user_id]);
        
        return redirect()->back()->with('success', 'Category Successfully Added. <a href="'.route('installer.newSys', urlencode($cat->name)).'">Add System</a>');
    }
     
    //  Form to add a new system
    public function newSystem($cat)
    {
        $dropDown = SystemCustDataTypes::orderBy('name', 'ASC')->get();
        
        return view('installer.form.newSystem', [
            'cat'      => $cat,
            'dropDown' => $dropDown
        ]);
    }
    
    //  Submit the new system
    public function submitSys($cat, Request $request)
    {
        $catName = SystemCategories::where('name', urldecode($cat))->first()->cat_id;
        $sysData = SystemTypes::create([
            'cat_id' => $catName,
            'name' => $request->name,
            'parent_id' => null,
            'folder_location' => str_replace(' ', '_', $request->name)
        ]);
        $sysID = $sysData->sys_id;
        $i = 0;
        
        foreach($request->custField as $field)
        {
            if(!empty($field))
            {
                $id = SystemCustDataTypes::where('name', $field)->first();

                if(is_null($id))
                {
                    $newField = SystemCustDataTypes::create([
                        'name' => $field
                    ]);
                    $id = $newField->data_type_id;
                }
                else
                {
                    $id = $id->data_type_id;
                }

                SystemCustDataFields::create([
                    'sys_id' => $sysID,
                    'data_type_id' => $id,
                    'order' => $i
                ]);

                $i++;
            }
        }
        
        Log::info('New System Created', ['cat_id' => $request->catName, 'sys_name' => $request->name, 'user_id' => Auth::user()->user_id]);
        
        return redirect()->back()->with('success', 'System Successfully Added');//
    }
    
    //  Form to edit an existing system
    public function editSystem($sysName)
    {        
        $sysData = SystemTypes::where('name', urldecode($sysName))->first();
        $dropDown = SystemCustDataTypes::orderBy('name', 'ASC')->get();
        $dataType = SystemCustDataFields::where('sys_id', $sysData->sys_id)
            ->join('system_cust_data_types', 'system_cust_data_types.data_type_id', '=', 'system_cust_data_fields.data_type_id')
            ->orderBy('order', 'ASC')
            ->get();
        
        return view('installer.form.editSystem', [
            'dropDown' => $dropDown,
            'fields'   => $dataType,
            'name'     => $sysData->name,
            'sysID'    => $sysData->sys_id
        ]);
    }
    
    //  Submit the edited system
    public function submitEditSystem($sysID, Request $request)
    {
        $sysName = SystemTypes::find($sysID)->name;
        
        //  Change the name of the system if it has been modified
        if($sysName !== $request->name)
        {
            SystemTypes::find($sysID)->update([
                'name' => $request->name
            ]);
        }
        
        //  Update the Customer Information
        $dataType = SystemCustDataFields::where('sys_id', $sysID)
            ->join('system_cust_data_types', 'system_cust_data_types.data_type_id', '=', 'system_cust_data_fields.data_type_id')
            ->get();
        
        $i = 0;
        foreach($request->custField as $field)
        {
            $found = false;
            if(!empty($field))
            {
                //  Check if the field already exists
                foreach($dataType as $type)
                {
                    if($type->name === $field)
                    {
                        $found = true;
                        //  See if the order has changed
                        if($type->order != $i)
                        {
                            SystemCustDataFields::find($type->field_id)->update(
                            [
                                'order' => $i
                            ]);
                        }
                    }
                }
                //  If the field does not exist, create it
                if(!$found)
                {
                    $id = SystemCustDataTypes::where('name', $field)->first();

                    if(is_null($id))
                    {
                        $newField = SystemCustDataTypes::create([
                            'name' => $field
                        ]);
                        $id = $newField->data_type_id;
                    }
                    else
                    {
                        $id = $id->data_type_id;
                    }

                    SystemCustDataFields::create([
                        'sys_id' => $sysID,
                        'data_type_id' => $id,
                        'order' => $i
                    ]);
                }
            }
            
            $i++;
        }
        
        Log::info('System Updated', ['sys_id' => $sysID, 'user_id' => Auth::user()->user_id]);
        
        return redirect(route('installer.editSystem', $request->name))->with('success', 'System Successfully Updated');
    }
}
