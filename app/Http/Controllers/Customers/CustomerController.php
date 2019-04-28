<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
use App\SystemTypes;
use App\CustomerFavs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Landing page to search for customer
    public function index()
    {
        $sysTypes = SystemTypes::all();
        
        $sysArr = [];
        foreach($sysTypes as $sys)
        {
            $sysArr[] = $sys->name;
        }
        
        return view('customer.index', [
            'sysTypes' => $sysArr
        ]);
    }
    
    //  Search for the customer based on their ID - For new file link form
    public function searchID($id)
    {
        $id = urldecode($id);
        if($id === 'NULL')
        {
            $id = '';
        }
        
        //  Determine if a customer number/name has already been entered
        if(!empty($id))
        {
            $split = explode(' ', $id);
            if(isset($split[1]) && $split[1] === '-')
            {
                $id = $split[0];
            }
        }
        
        $res = Customers::where('cust_id', 'like', '%'.$id.'%')
            ->orWhere('name', 'like', '%'.$id.'%')
            ->where('active', 1)
            ->orderBy('name')
            ->get();
        
        return response()->json($res);
    }
    
    //  Return a full JSON array of the available customers
    public function search()
    {
        $customers = Customers::
                with('CustomerSystems.SystemTypes')
                ->get();
        
        $custList = [];
        foreach($customers as $cust)
        {
            $sysArr = '';
            foreach($cust->CustomerSystems as $sys)
            {
                $sysArr .= $sys->SystemTypes->name.'<br />';
            }
            
            $custList[] = [
                'cust_id' => $cust->cust_id,
                'name' => $cust->name,
                'dba'  => $cust->dba_name,
                'city' => $cust->city.', '.$cust->state,
                'url'  => route('customer.details', [$cust->cust_id, urlencode($cust->name)]),
                'sys'  => $sysArr
            ];            
        }
        
        return response()->json($custList);
    }
    
    //  Check to see if a customer ID already exists
    public function checkID($id)
    {
        $cust = Customers::find($id);
        
        if($cust === null)
        {
            return response()->json(['dup' => false]);
        }
        
        return response()->json(['dup' => true, 'url' => route('customer.details', [$cust->cust_id, urlencode($cust->name)])]);
    }
    
    //  Toggle whether or not the customer is listed as a user favorite
    public function toggleFav($action, $id)
    {
        switch ($action)
        {
            case 'add':
                CustomerFavs::create([
                    'user_id' => Auth::user()->user_id,
                    'cust_id' => $id
                ]);
                break;
            case 'remove':
                $custFav = CustomerFavs::where('user_id', Auth::user()->user_id)->where('cust_id', $id)->first();
                $custFav->delete();
                break;
        }
        
        return response()->json(['success' => true]);
    }
}
