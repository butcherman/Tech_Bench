<?php

use App\Models\Customer;
use App\Models\CustomerEquipment;
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
        $this->migrateCustomerEquipment();
        $this->cleanup();
    }

    /**
     * Migrate Customer Equipment to new schema
     */
    public function migrateCustomerEquipment()
    {
        $customerEquipment = CustomerEquipment::withTrashed()->get();

        foreach ($customerEquipment as $equip) {
            DB::table('customer_site_equipment')->insert([
                'cust_site_id' => $equip->cust_id,
                'cust_equip_id' => $equip->cust_equip_id,
            ]);

            // Verify which customer and site this item is attached to
            $cust = Customer::withTrashed()->find($equip->cust_id);
            if ($cust) {

                if ($cust->parent_id) {
                    $equip->update([
                        'cust_id' => $cust->parent_id,
                    ]);
                }
            }

            //  If equipment is shared, add the other sites it is shared with
            if ($equip->shared) {
                $custList = Customer::where('parent_id', $equip->cust_id)->get();
                foreach ($custList as $cust) {
                    DB::table('customer_site_equipment')->insert([
                        'cust_site_id' => $cust->cust_id,
                        'cust_equip_id' => $equip->cust_equip_id,
                    ]);
                }
            }
        }
    }

    /**
     * Remove Shared Column from Customer Equipment Table
     */
    public function cleanup()
    {
        // Remove Shared Column from all other customer tables
        Schema::table('customer_equipment', function (Blueprint $table) {
            $table->dropColumn('shared');
        });
    }
};
