<?php

use App\Models\Customer;
use App\Models\CustomerEquipment;
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
        $this->migrateCustomerEquipment();
        $this->cleanup();
    }

    /**
     * Migrate Customer Equipment to new schema
     */
    public function migrateCustomerEquipment()
    {
        $customerEquipment = CustomerEquipment::withTrashed()
            ->get();

        foreach ($customerEquipment as $equip) {
            $equip->CustomerSite()->attach($equip->cust_id);

            // Verify the equipment is applied to the primary Customer ID
            $cust = Customer::withTrashed()->find($equip->cust_id);
            if ($cust->parent_id) {
                $equip->update([
                    'cust_id' => $cust->parent_id,
                ]);
            }

            //  If equipment is shared, add the other sites it is shared with
            if ($equip->shared) {
                $siteList = CustomerSite::where('cust_id', $equip->cust_id)
                    ->get()
                    ->pluck('cust_site_id');
                $equip->CustomerSite()->sync($siteList);
            }
        }
    }

    /**
     * Remove Shared Column from Customer Equipment Table
     */
    public function cleanup()
    {
        Schema::table('customer_equipment', function (Blueprint $table) {
            $table->dropColumn('shared');
        });
    }
};
