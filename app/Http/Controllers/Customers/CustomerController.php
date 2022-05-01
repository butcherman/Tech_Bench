<?php

namespace App\Http\Controllers\Customers;

use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Traits\FileTrait;
use App\Http\Controllers\Controller;
use App\Events\Customers\Admin\CustomerRestoredEvent;
use App\Events\Customers\Admin\CustomerForceDeletedEvent;

use App\Events\Customers\NewCustomerCreated;
use App\Events\Customers\CustomerDetailsUpdated;
use App\Events\Customers\CustomerDeactivatedEvent;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\EquipmentType;
use App\Models\PhoneNumberType;
use App\Models\CustomerFileType;
use App\Models\UserCustomerRecent;
use App\Models\UserCustomerBookmark;

use App\Http\Requests\Customers\NewCustomerRequest;
use App\Http\Requests\Customers\EditCustomerRequest;
use App\Jobs\CustomerRemoveBookmarksJob;

class CustomerController extends Controller
{
    use FileTrait;

    /**
     * Search page for finding a customer
     */
    public function index(Request $request)
    {
        return Inertia::render('Customers/Index', [
            'create'      => $request->user()->can('create', Customer::class),
            'equip_types' => EquipmentType::orderBy('cat_id')->get()->pluck('name')->values(),
        ]);
    }

    /**
     * Show the form for creating a new Customer
     */
    public function create()
    {
        $this->authorize('create', Customer::class);
        return Inertia::render('Customers/Create');
    }

    /**
     * Create a new Customer
     */
    public function store(NewCustomerRequest $request)
    {
        $cust         = $request->toArray();
        $cust['slug'] = $this->checkSlug(Str::slug($request->name), $request->city);

        $newCust      = Customer::create($cust);

        event(new NewCustomerCreated($newCust));
        return redirect(route('customers.show',$newCust->slug))->with(['message' => 'New Customer Created', 'type' => 'success']);
    }

    /**
     * Display the Customers Information
     */
    public function show($id)
    {
        //  Check if we are passing the customer slugged name, or customer ID number
        if(is_numeric($id))
        {
            //  To keep things uniform, redirect to a link that has the customers name rather than the ID
            $customer = Customer::findOrFail($id);
            return redirect(route('customers.show', $customer->slug));
        }

        //  Pull the customers information
        try
        {
            $customer = Customer::where('slug', $id)
                        ->orWhere('cust_id', $id)
                        ->with('Parent')
                        ->with('CustomerEquipment.CustomerEquipmentData')
                        ->with('ParentEquipment.CustomerEquipmentData')
                        ->with('CustomerContact.CustomerContactPhone.PhoneNumberType')
                        ->with('ParentContact.CustomerContactPhone.PhoneNumberType')
                        ->with('CustomerNote')
                        ->with('ParentNote')
                        ->with('CustomerFile.FileUpload')
                        ->with('ParentFile.FileUpload')
                        ->firstOrFail();
        }
        catch(ModelNotFoundException $e)
        {
            report($e);
            abort(404, 'The Customer you are looking for cannot be found');
        }

        //  Determine if the customer is bookmarked by the user
        $isFav    = UserCustomerBookmark::where('user_id', Auth::user()->user_id)
                        ->where('cust_id', $customer->cust_id)
                        ->count();

        //  Add customer to the users 'recent' table
        UserCustomerRecent::firstOrCreate([
            'user_id' => Auth::user()->user_id,
            'cust_id' => $customer->cust_id
        ])->touch();

        return Inertia::render('Customers/Show', [
            'details'        => $customer,
            'phone_types'    => PhoneNumberType::all(),
            'file_types'     => CustomerFileType::all(),
            //  User Permissions for customers
            'user_data' => [
                'fav'        => $isFav,                                                  //  Customer is bookmarked by the user
                'edit'       => Auth::user()->can('update', $customer),                  //  User is allowed to edit the customers basic details
                'manage'     => Auth::user()->can('manage', $customer),                  //  User can recover deleted items
                'deactivate' => Auth::user()->can('delete', $customer),                  //  User can deactivate the customer profile
                'equipment'  => [
                    'create' => Auth::user()->can('create', CustomerEquipment::class),   //  If user can add equipment
                    'update' => Auth::user()->can('update', CustomerEquipment::class),   //  If user can edit equipment
                    'delete' => Auth::user()->can('delete', CustomerEquipment::class),   //  If user can delete equipment
                ],
                'contacts'   => [
                    'create' => Auth::user()->can('create', CustomerContact::class),     //  If user can add contact
                    'update' => Auth::user()->can('update', CustomerContact::class),     //  If user can edit contact
                    'delete' => Auth::user()->can('delete', CustomerContact::class),     //  If user can delete contact
                ],
                'notes'      => [
                    'create' => Auth::user()->can('create', CustomerNote::class),        //  If user can add note
                    'update' => Auth::user()->can('update', CustomerNote::class),        //  If user can edit note
                    'delete' => Auth::user()->can('delete', CustomerNote::class),        //  If user can delete note
                ],
                'files'     => [
                    'create' => Auth::user()->can('create', CustomerFile::class),        //  If user can add file
                    'update' => Auth::user()->can('update', CustomerFile::class),        //  If user can edit file
                    'delete' => Auth::user()->can('delete', CustomerFile::class),        //  If user can delete file
                ],
            ],
        ]);
    }

    /**
     * Update the customers basic details
     */
    public function update(EditCustomerRequest $request, $id)
    {
        $cust         = $request->toArray();
        $cust['slug'] = $this->checkSlug(Str::slug($request->name), $request->city, $id);

        $data = Customer::findOrFail($id);
        $data->update($cust);
        event(new CustomerDetailsUpdated($data, $id));

        return redirect(route('customers.show', $cust['slug']))->with(['message' => 'Customer Details Updated', 'type' => 'success']);
    }

    /**
     * Soft Delete a Customer from the database
     */
    public function destroy($id)
    {
        $cust = Customer::findOrFail($id);
        $this->authorize('delete', $cust);
        $cust->delete();

        dispatch(new CustomerRemoveBookmarksJob($cust));

        event(new CustomerDeactivatedEvent($cust));
        return redirect(route('customers.index'))->with(['message' => 'Customer '.$cust->name.' deactivated', 'type' => 'danger']);
    }

    /**
     * Restore a soft deleted customer
     */
    public function restore(Request $idArr)
    {
        $this->authorize('restore', Customer::class);

        //  Customer ID's are in an array
        foreach($idArr->list as $cust)
        {
            $cust = Customer::onlyTrashed()->where('cust_id', $cust['cust_id'])->firstOrFail();
            $cust->restore();

            event(new CustomerRestoredEvent($cust));
        }

        return back()->with([
            'message' => 'Customers Restored',
            'type'    => 'success',
        ]);
    }

    /**
     * Permanently Delete a customer and all associated files and information
     */
    public function forceDelete(Request $id)
    {
        $this->authorize('forceDelete', Customer::class);

        //  Customer ID's are in an array
        foreach($id->list as $cust)
        {
            $cust     = Customer::onlyTrashed()->where('cust_id', $cust['cust_id'])->firstOrFail();
            $fileList = [];

            //  Get all of the files that are attached to the customer
            $files = CustomerFile::where('cust_id', $cust->cust_id)->get();
            foreach($files as $file)
            {
                $fileList[] = $file->file_id;
            }

            $cust->forceDelete();

            //  Delete the files from the Storage System
            foreach($fileList as $file)
            {
                $this->deleteFile($file);
            }

            event(new CustomerForceDeletedEvent($cust));
        }

        return back()->with([
            'message' => 'Customers Deleted',
            'type'    => 'danger',
        ]);
    }

    /**
     * Check the database to see if the supplied customer slug already exists
     */
    protected function checkSlug($slug, $appends = null, $ignore = null)
    {
        $index   = 0;
        $newSlug = $slug;
        while(Customer::where('slug', $newSlug)->where('cust_id', '!=', $ignore)->first())
        {
            if($index == 0 && !is_null($appends))
            {
                $newSlug = Str::slug($slug.'-'.$appends);
                $index++;
            }
            else
            {
                $newSlug = Str::slug($slug.'-'.++$index);
            }
        }

        return $newSlug;
    }
}
