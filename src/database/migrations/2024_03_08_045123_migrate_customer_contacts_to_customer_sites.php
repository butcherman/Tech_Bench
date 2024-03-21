<?php

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerSite;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer_contacts', function (Blueprint $table) {
            $table->boolean('local')->after('note')->default(false);
            $table->boolean('decision_maker')->after('local')->default(false);
        });

        $this->migrateCustomerContacts();
        $this->cleanup();
    }

    /**
     * Migrate Customer Contacts to new Schema
     */
    public function migrateCustomerContacts()
    {
        $customerContacts = CustomerContact::withTrashed()->get();

        foreach ($customerContacts as $contact) {
            $contact->CustomerSite()->attach($contact->cust_id);

            // Verify the contact is applied to the primary Customer ID
            $cust = Customer::withTrashed()->find($contact->cust_id);
            if ($cust->parent_id) {
                $contact->update([
                    'cust_id' => $cust->parent_id,
                ]);
            }

            // If Contact is shared, att the other sites it is shared with
            if ($contact->shared) {
                $siteList = CustomerSite::where('cust_id', $contact->cust_id)
                    ->get()->pluck('cust_site_id')->toArray();
                $contact->CustomerSite()->sync($siteList);
            }
        }
    }

    /**
     * Remove Shared Column from Customer Contacts Table
     */
    public function cleanup()
    {
        Schema::table('customer_contacts', function (Blueprint $table) {
            $table->dropColumn('shared');
        });
    }
};
