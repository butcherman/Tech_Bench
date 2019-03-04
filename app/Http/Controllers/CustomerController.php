<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\SystemTypes;
use App\SystemCategories;
use App\CustomerSystems;
use App\CustomerNotes;
use App\CustomerFavs;
use App\Customers;
use PDF;

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
        foreach($systems as $sys)
        {
            foreach($sys->SystemTypes as $s)
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
        if(!empty($request->system))
        {
            $customerData = Customers::where('name', 'like', '%'.$request->customer.'%')
                ->where('city', 'like', '%'.$request->city.'%')

                ->with('CustomerSystems.SystemTypes')
                ->whereHas('CustomerSystems', function($q) use($request)
                {
                   $q->where('sys_id', $request->system);
                })
                ->get();
        }
        else
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
    
    //  Search for the customer based on their ID - For new file link form
    public function searchID(Request $request)
    {
        //  Determine if a customer number/name has already been entered
        if(!empty($request->name))
        {
            $split = explode(' ', $request->name);
            if($split[1] === '-')
            {
                $request->name = $split[0];
            }
        }
        
        $res = Customers::where('cust_id', 'like', '%'.$request->name.'%')
            ->orWhere('name', 'like', '%'.$request->name.'%')
            ->orderBy('name')
            ->get();
        
        return view('customer.link_list', [
            'list' => $res
        ]);
    }
    
    //  Check to see if a customer ID already exists
    public function checkId($id)
    {
        $cust = Customers::find($id);
        
        if($cust === null)
        {
            return 'false';
        }
        
        return urlencode($cust->name);
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
    
    //  Download a note as a PDF file
    public function generatePDF($noteID)
    {
        $note = CustomerNotes::find($noteID);
        $cust = Customers::find($note->cust_id);
        
        $pdf = PDF::loadView('pdf.customerNote', [
            'cust_name'   => $cust->name,
            'note_subj'   => $note->subject,
            'description' => $note->description,
        ]);
        
        return $pdf->download($cust->name.' - Note: '.$note->subject.'.pdf');
    }
}
