<?php

use App\Models\Customer;
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
        $this->removeChildCustomers();
        $this->cleanupCustomersTable();
    }

    protected function removeChildCustomers()
    {
        $childList = Customer::whereNotNull('parent_id')
            ->withTrashed()
            ->get();

        foreach ($childList as $cust) {
            $cust->forceDelete();
        }
    }

    protected function cleanupCustomersTable(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn([
                'parent_id',
                'address',
                'city',
                'state',
                'zip',
            ]);
            $table->text('deleted_reason')->nullable()->after('slug');
        });
    }
};
