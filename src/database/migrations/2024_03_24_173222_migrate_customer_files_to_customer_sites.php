<?php

use App\Models\Customer;
use App\Models\CustomerFile;
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
        Schema::table('customer_files', function (Blueprint $table) {
            $table->unsignedBigInteger('cust_equip_id')
                ->after('file_type_id')
                ->nullable();
            $table->foreign('cust_equip_id')
                ->references('cust_equip_id')
                ->on('customer_equipment')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        $this->migrateCustomerFiles();
        $this->cleanup();
    }

    protected function migrateCustomerFiles(): void
    {
        // $customerFiles = CustomerFile
        $customerFiles = CustomerFile::withTrashed()->get();

        foreach ($customerFiles as $file) {
            // Verify the file is applied to the primary Customer ID
            $cust = Customer::withTrashed()->find($file->cust_id);
            if ($cust->parent_id) {
                $file->update(['cust_id' => $cust->parent_id]);
            }

            // If file is shared, add the sites it is shared with
            if ($file->shared) {
                $siteList = CustomerSite::where('cust_id', $file->cust_id)
                    ->get()
                    ->pluck('cust_site_id')
                    ->toArray();
                $file->Sites()->sync($siteList);
            }
        }
    }

    protected function cleanup(): void
    {
        Schema::table('customer_files', function (Blueprint $table) {
            $table->dropColumn('shared');
        });
    }
};
