<?php

use App\Models\Customer;
use App\Models\CustomerNote;
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
        Schema::table('customer_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('cust_equip_id')
                ->after('details')
                ->nullable();
            $table->foreign('cust_equip_id')
                ->references('cust_equip_id')
                ->on('customer_equipment')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        $this->migrateCustomerNotes();
        $this->cleanup();
    }

    /**
     * Migrate Customer Notes to new Schema
     */
    public function migrateCustomerNotes()
    {
        $customerNotes = CustomerNote::withTrashed()->get();

        foreach ($customerNotes as $note) {
            // Verify the note is applied to the primary Customer ID
            $cust = Customer::withTrashed()->find($note->cust_id);
            if ($cust->parent_id) {
                $note->update([
                    'cust_id' => $cust->parent_id,
                ]);
            }

            // If Note is shared, add teh other sites it is shared with
            if ($note->shared) {
                $siteList = CustomerSite::where('cust_id', $note->cust_id)
                    ->get()->pluck('cust_site_id')->toArray();
                $note->CustomerSite()->sync($siteList);
            }
        }
    }

    /**
     * Remote Shared Column from Customer Notes Table
     */
    public function cleanup()
    {
        Schema::table('customer_notes', function (Blueprint $table) {
            $table->dropColumn('shared');
        });
    }
};
