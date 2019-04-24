<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
use App\SystemTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                'name' => $cust->name,
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
}
