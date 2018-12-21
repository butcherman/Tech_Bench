<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\SystemTypes;
use App\SystemCategories;
use App\CustomerSystems;
use App\CustomerFavs;
use App\Customers;

class CustomerController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Landing page brings up the customer search form
    public function index()
    {
        $systems = SystemCategories::with('SystemTypes')
            ->orderBy('cat_id', 'asc')
            ->get();
        
        $sysArr = [];
        foreach ($systems as $sys)
        {
            foreach ($sys->SystemTypes as $s)
            {
                $sysArr[$sys->name][$s->sys_id] = $s->name;
            }
        }
        
        return view('customer.index', [
            'systems' => $sysArr
        ]);
    }
    
    //  Search for a customer
    public function search(Request $request)
    {
        //  Run different request based on if system field is filled out or not
        if (!empty($request->system))
        {
            $customerData = Customers::where('name', 'like', '%'.$request->customer.'%')
                ->where('city', 'like', '%'.$request->city.'%')

                ->with('CustomerSystems.SystemTypes')
                ->whereHas('CustomerSystems', function($q) use($request)
                {
                    $q->where('sys_id', $request->system);
                })
                ->get();
        } else
        {
            $customerData = Customers::where('name', 'like', '%'.$request->customer.'%')
                ->where('city', 'like', '%'.$request->city.'%')
                ->with('CustomerSystems.SystemTypes')
                ->get();
        }

        return view('customer.searchResults', [
            'results' => $customerData
        ]);
    }
    
    //  Toggle whether or not the customer is listed as a user favorite
    public function toggleFav($action, $custID)
    {
        switch ($action)
        {
            case 'add':
                CustomerFavs::create([
                    'user_id' => Auth::user()->user_id,
                    'cust_id' => $custID
                ]);
                break;
            case 'remove':
                $custFav = CustomerFavs::where('user_id', Auth::user()->user_id)->where('cust_id', $custID)->first();
                $custFav->delete();
                break;
        }        
    }
}
