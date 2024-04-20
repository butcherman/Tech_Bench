<?php

use App\Models\Customer;
use App\Models\CustomerSite;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            if (! $cust->parent_id) {
                $cust->primary_site_id = $cust->cust_id;
                $cust->save();
            }
        }
    }
};
