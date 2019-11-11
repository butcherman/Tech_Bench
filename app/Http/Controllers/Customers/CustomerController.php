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
use App\Http\Resources\SystemTypesCollection;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Landing page to search for customer
    public function index()
    {
        $systems = new SystemTypesCollection(SystemTypes::orderBy('cat_id', 'ASC')->orderBy('name', 'ASC')->get());

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('customer.index', [
            'sysTypes' => $systems
        ]);
    }

    //  Search for a customer
    public function search(Request $request)
    {
        $request->validate([
            'sortField' => 'required',
            'sortType'  => 'required',
            'perPage'   => 'required'
        ]);

        Log::debug('Request Data - ', $request->toArray());

        if(isset($request->name) || isset($request->city) || isset($request->system))
        {
            $searchResults = new CustomersCollection(
                Customers::orderBy($request->sortField, $request->sortType)
                    //  Search the name, dba name, and cust id columns
                    ->where(function($query) use ($request)
                    {
                        $query->where('name', 'like', '%' . $request->name . '%')
                            ->orWhere('cust_id', 'like', '%' . $request->name . '%')
                            ->orWhere('dba_name', 'like', '%' . $request->name . '%');
                    })
                    //  Search the city column
                    ->where('city', 'like', '%' . $request->city . '%')
                    //  Include the customers systems
                    ->with('CustomerSystems.SystemTypes')
                    //  If the system field is present - search for system type
                    ->when($request->system, function($query) use ($request)
                    {
                        $query->whereHas('CustomerSystems.SystemTypes', function($query) use ($request)
                        {
                            $query->where('sys_id', $request->system);
                        });
                    })
                    ->paginate($request->perPage)
            );
        }
        else
        {
            $searchResults = new CustomersCollection(Customers::orderBy($request->sortField, $request->sortType)->with('CustomerSystems.SystemTypes')->paginate($request->perPage));
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
