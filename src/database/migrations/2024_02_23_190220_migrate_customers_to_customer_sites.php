<?php

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerSite;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('primary_site_id')
                ->after('cust_id')
                ->nullable();
            $table->foreign('primary_site_id')
                ->references('cust_site_id')
                ->on('customer_sites')
                ->onUpdate('cascade');
        });

        $this->migrate();
        // $this->migrateCustomerContacts();
        // $this->migrateCustomerNotes();
        // $this->migrateCustomerFiles();
        // $this->cleanup();
    }

    /**
     * Migrate data from the customers table to this new table
     */
    protected function migrate()
    {
        $custList = Customer::withTrashed()->get();

        // Cycle through the list of customers and create individual sites
        foreach ($custList as $cust) {
            CustomerSite::create([
                'cust_site_id' => $cust->cust_id,
                'cust_id' => $cust->parent_id ? $cust->parent_id : $cust->cust_id,
                'site_name' => $cust->name,
                'site_slug' => $cust->slug,
                'address' => $cust->address,
                'city' => $cust->city,
                'state' => $cust->state,
                'zip' => $cust->zip,
            ]);

            // Add the Primary Site to the Customer Profile
            if (!$cust->parent_id) {
                $cust->primary_site_id = $cust->cust_id;
                $cust->save();
            }
        }
    }

    /**
     * Cleanup unneeded tables and data
     */
    protected function cleanup()
    {
        $custList = Customer::whereNotNull('parent_id')->withTrashed()->get();
        foreach ($custList as $cust) {
            $cust->forceDelete();
        }

        // Remove Parent ID and add Deleted Reason to Customers Table
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'address', 'city', 'state', 'zip']);
            $table->text('deleted_reason')->nullable()->after('slug');
        });
    }




    /**
     * Migrate Customer Files to new schema
     */
    // public function migrateCustomerFiles()
    // {
    //     $migrationData = CustomerFile::withTrashed()->get();

    //     foreach ($migrationData as $data) {
    //         CustomerFileSite::create([
    //             'cust_site_id' => $data->cust_id,
    //             'cust_file_id' => $data->cust_file_id,
    //         ]);

    //         // Verify which customer and site this item is attached to
    //         $cust = Customer::withTrashed()->find($data->cust_id);
    //         if ($cust) {

    //             if ($cust->parent_id) {
    //                 $data->update([
    //                     'cust_id' => $cust->parent_id,
    //                 ]);
    //             }
    //         }

    //         //  If equipment is shared, add the other sites it is shared with
    //         if ($data->shared) {
    //             $custList = Customer::where('parent_id', $data->cust_id)->get();
    //             foreach ($custList as $cust) {
    //                 CustomerFileSite::create([
    //                     'cust_site_id' => $cust->cust_id,
    //                     'cust_file_id' => $data->cust_file_id,
    //                 ]);
    //             }
    //         }
    //     }
    // }
};