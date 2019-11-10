<?php

namespace App\Http\Controllers\Customers;

use App\Customers;
use App\SystemTypes;
use App\CustomerFavs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\CustomersCollection;

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

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('customer.index', [
            // 'sysTypes' => $sysArr
        ]);
    }

    //  Search for a customer
    public function search(Request $request)
    {
        // $customers = Customers::
        //         with('CustomerSystems.SystemTypes')
        //         ->get();

        // $custList = [];
        // foreach($customers as $cust)
        // {
        //     $sysArr = '';
        //     foreach($cust->CustomerSystems as $sys)
        //     {
        //         $sysArr .= $sys->SystemTypes->name.'<br />';
        //     }

        //     $custList[] = [
        //         'cust_id' => $cust->cust_id,
        //         'name' => $cust->name,
        //         'dba'  => $cust->dba_name,
        //         'city' => $cust->city.', '.$cust->state,
        //         'url'  => route('customer.details', [$cust->cust_id, urlencode($cust->name)]),
        //         'sys'  => $sysArr
        //     ];


        // }

        // Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        // Log::debug('Customer Data - ', $custList);
        // return response()->json($custList);


        // $searchResults = new CustomerCollection(
        //     Customers::where('cust_id', $request->search)
        //         ->orWhere('name', $request->search)
        //         ->orWhere('dba_name', $request->search)
        //         ->get()
        //     );

        // dd($request->search);
        $orderBy = $request->sortField ? $request->sortField : 'name';
        $orderUp = $request->sortType ? $request->sortType : 'ASC';
        $paginate = $request->perPage ? $request->perPage : 25;

        $name = $request->name ? $request->name : null;
        $city = $request->city ? $request->city : null;

        // $searchSys = $request->searchSys ? $request->searchSys : false;

        Log::debug('paramaters', $request->toArray());

        // if($request->search)
        // {
        //     $searchResults = new CustomerCollection(
        //     Customers::where('cust_id', 'like', '%'.$request->search.'%')
        //         ->orWhere('name', 'like', '%'.$request->search.'%')
        //         ->orWhere('dba_name', 'like', '%'.$request->search.'%')
        //         ->get()
        //     );
        // }
        // else
        // {
            // }





            // $searchResults = new CustomersCollection(Customers::orderBy($orderBy, $orderUp)->with('CustomerSystems')->paginate($paginate));


            if(isset($request->name) || isset($request->city))
            {
                // $name = $request->name ? $request->name : '';
                // $city = $request->city ? $request->city : '';

                $searchResults = new CustomersCollection(
                    Customers::orderBy($orderBy, $orderUp)
                        ->where('cust_id', 'like', '%'.$request->name.'%')
                        ->orWhere('name', 'like', '%'. $request->name.'%')
                        ->orWhere('dba_name', 'like', '%'. $request->name.'%')
                        // ->orWhere('city', 'like', '%'.$city.'%')
                        ->with('CustomerSystems.SystemTypes')
                        ->paginate($paginate));
                Log::debug('triggered');
            }
            else
            {
                $searchResults = new CustomersCollection(Customers::orderBy($orderBy, $orderUp)->with('CustomerSystems.SystemTypes')->paginate($paginate));
            }







            return $searchResults;
        }

        //  Check to see if a customer ID already exists
    public function checkID($id)
    {
        $cust = Customers::find($id);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        if($cust === null)
        {
            Log::debug('Customer ID - '.$id.' is available to use');
            return response()->json(['dup' => false]);
        }


        Log::debug('Customer ID is in use by - '.$cust->name);
        return response()->json(['dup' => true, 'url' => route('customer.details', [$cust->cust_id, urlencode($cust->name)])]);
    }

    //  Toggle whether or not the customer is listed as a user favorite
    public function toggleFav($action, $id)
    {
        switch($action)
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

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Customer Bookmark Updated.', [
            'user_id' => Auth::user()->user_id,
            'cust_id' => $id,
            'action'  => $action
        ]);
        return response()->json(['success' => true]);
    }
}
