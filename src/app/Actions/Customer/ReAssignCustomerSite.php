<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use Illuminate\Support\Facades\Log;

class ReAssignCustomerSite
{
    /**
     * Site that is being moved to a new Customer ID.
     *
     * @var CustomerSite
     */
    protected $movingSite;

    /**
     * Destination Customer for the moving site.
     *
     * @var Customer
     */
    protected $toCustomer;

    /**
     * If this site is the only site belonging to the original customer ID.
     *
     * @var bool
     */
    protected $isSolo;

    /**
     * Re-Assign a customer site from one customer ID to another. Process will
     * move any files, contacts, and notes that are only for this site, or
     * equipment that is only attached to this site.
     */
    public function __invoke(CustomerSite $movingSite, Customer $toCustomer): void
    {
        Log::debug('Moving Customer Site to new Customer', [
            'moving_site' => $movingSite->toArray(),
            'to_customer' => $toCustomer->toArray(),
        ]);

        $this->movingSite = $movingSite;
        $this->toCustomer = $toCustomer;

        // Note if this is the only site belonging to the losing customer
        $this->isSolo = $this->movingSite->Customer->SiteCount === 1;

        // Perform the move sequence
        $this->moveSiteEquipment();
        $this->moveSiteContacts();
        $this->moveSiteNotes();
        $this->moveSiteFiles();
        $this->finalize();
    }

    /**
     * Re-assign the selected model to the new customer id
     */
    protected function reAssignModel(mixed $model): void
    {
        $model->cust_id = $this->toCustomer->cust_id;
        $model->save();
    }

    /**
     * Cycle through model and move items only belonging to this one site
     * Items belonging to multiple sites will be detached from the move site
     */
    protected function cycleModel(mixed $modelList): void
    {
        if ($modelList) {
            foreach ($modelList as $model) {
                if ($model->CustomerSite->count() === 1) {
                    Log::info(
                        'Moving data from Customer ID'.$model->cust_id.
                            ' to Customer ID '.$this->toCustomer->cust_id,
                        $model->toArray()
                    );

                    $this->reAssignModel($model);
                } else {
                    $model->CustomerSite()
                        ->detach($this->movingSite->cust_site_id);
                }
            }
        }
    }

    /*
    |---------------------------------------------------------------------------
    | Action Sequence Functions
    |---------------------------------------------------------------------------
    */

    /**
     * Move equipment that are only for this one site over to new customer
     */
    protected function moveSiteEquipment(): void
    {
        Log::debug('Checking for Customer Equipment that can be moved');

        $availableEquipment = $this->movingSite->SiteEquipment;

        foreach ($availableEquipment as $equip) {
            /**
             * If the equipment only belongs to this site, it will be moved
             * If not, it will be detached from the site
             */
            if ($equip->CustomerSite->count() === 1) {
                Log::info('Moving Customer Equipment ID '.
                    $equip->cust_equip_id.' from Customer ID '.$equip->cust_id.
                    ' to Customer ID'.$this->toCustomer->cust_id);

                $this->reAssignModel($equip);

                // Move any Notes attached to this equipment
                $moveNotes = CustomerNote::where(
                    'cust_equip_id',
                    $equip->cust_equip_id
                )
                    ->get();

                foreach ($moveNotes as $note) {
                    $this->reAssignModel($note);
                }

                // Move any Files attached to this equipment
                $moveFiles = CustomerFile::where(
                    'cust_equip_id',
                    $equip->cust_equip_id
                )
                    ->get();

                foreach ($moveFiles as $file) {
                    $this->reAssignModel($file);
                }
            } else {
                // Equipment belongs to multiple sites, detach it from this site.
                $equip->CustomerSite()->detach($this->movingSite->cust_site_id);
            }
        }
    }

    /**
     * Move contacts that are only for this one site over to new customer
     */
    protected function moveSiteContacts(): void
    {
        Log::debug('Checking for Customer Contacts that can be moved');

        $availableContacts = $this->movingSite->SiteContact;

        $this->cycleModel($availableContacts);
    }

    /**
     * Move notes that are only for this one site over to new customer
     */
    protected function moveSiteNotes(): void
    {
        Log::debug('Checking for Customer Notes that can be moved');

        // If this is the only site attached to the customer, move all notes
        if ($this->isSolo) {
            $noteList = CustomerNote::where('cust_id', $this->movingSite->cust_id)
                ->whereNull('cust_equip_id')
                ->get();

            foreach ($noteList as $note) {
                $this->reAssignModel($note);
            }
        } else {
            $noteList = $this->movingSite->SiteNote;

            $this->cycleModel($noteList);
        }
    }

    /**
     * Move Files that are only for this one site over to new customer
     */
    protected function moveSiteFiles(): void
    {
        Log::debug('Checking for Customer Files that can be moved');

        // If this is the only site attached to customer, move all files
        if ($this->isSolo) {
            $fileList = CustomerFile::where('cust_id', $this->movingSite->cust_id)
                ->whereNull('cust_equip_id')
                ->get();

            foreach ($fileList as $file) {
                $this->reAssignModel($file);
            }
        } else {
            $fileList = $this->movingSite->SiteFile;

            $this->cycleModel($fileList);
        }
    }

    /**
     * Re-assign site to new customer
     * If this is the only site attached to the customer, delete the customer
     */
    protected function finalize(): void
    {
        $customer = $this->movingSite->Customer;

        // If the site is the customers primary site, remove it
        if ($customer->primary_site_id === $this->movingSite->cust_site_id) {
            $customer->primary_site_id = null;

            $customer->save();

            Log::notice('Primary Site for '.$customer->name.' has been removed');
        }

        if ($this->isSolo) {
            $customer->deleted_reason = 'Customer Site moved to new customer.  No sites remaining';
            $customer->save();
            $customer->delete();

            Log::notice(
                'Customer '.$customer->name.' has been disabled due to no active sites.'
            );
        }

        $this->movingSite->cust_id = $this->toCustomer->cust_id;
        $this->movingSite->save();
    }
}
