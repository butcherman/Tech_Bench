<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ReAssignCustomerSite
{
    /** @var CustomerSite */
    protected $moveSite;

    /** @var Customer */
    protected $toCustomer;

    /** @var bool */
    protected $isSolo;

    public function __invoke(Collection $request)
    {
        $this->moveSite = CustomerSite::find($request->get('moveSiteId'));
        $this->toCustomer = Customer::find($request->get('toCustomer'));

        // Determine if this is a solo site customer or not
        $fromCustomer = $this->moveSite->Customer;
        $this->isSolo = $fromCustomer->site_count === 1;

        $this->moveSiteEquipment();
        $this->moveSiteContacts();
        $this->moveSiteNotes();
        $this->moveSiteFiles();
        $this->finalize();
    }

    /**
     * Re-assign the selected model to the new customer id
     */
    protected function reAssignModel($model)
    {
        $model->cust_id = $this->toCustomer->cust_id;
        $model->save();
    }

    /**
     * Cycle through model and move items only belonging to this one site
     * Items belonging to multiple sites will be detached from the move site
     */
    protected function cycleModel($modelList)
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
                        ->detach($this->moveSite->cust_site_id);
                }
            }
        }
    }

    /**
     * Move equipment that are only for this one site over to new customer
     */
    protected function moveSiteEquipment()
    {
        Log::debug('Checking for Customer Equipment that can be moved');

        $availableEquipment = $this->moveSite->SiteEquipment;
        foreach ($availableEquipment as $equip) {
            /**
             * If the equipment only belongs to this site, it will be moved
             * If not, it will be detached from the site
             */
            if ($equip->CustomerSite->count() === 1) {
                Log::info('Moving Customer Equipment ID '.
                    $equip->cust_equip_id.' from Customer ID '.$equip->cust_id.
                    ' to Customer ID'.$this->toCustomer->cust_id);

                // $equip->update(['cust_id' => $this->toCustomer->cust_id]);
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
                $equip->CustomerSite()->detach($this->moveSite->cust_site_id);
            }
        }
    }

    /**
     * Move contacts that are only for this one site over to new customer
     */
    protected function moveSiteContacts()
    {
        Log::debug('Checking for Customer Contacts that can be moved');

        $availableContacts = $this->moveSite->SiteContact;
        $this->cycleModel($availableContacts);
    }

    /**
     * Move notes that are only for this one site over to new customer
     */
    protected function moveSiteNotes()
    {
        Log::debug('Checking for Customer Notes that can be moved');

        /**
         * If this is the only site attached to the customer, move all notes
         */
        if ($this->isSolo) {
            $noteList = CustomerNote::where('cust_id', $this->moveSite->cust_id)
                ->whereNull('cust_equip_id')
                ->get();
            foreach ($noteList as $note) {
                $this->reAssignModel($note);
            }
        } else {
            $noteList = $this->moveSite->SiteNote;
            $this->cycleModel($noteList);
        }
    }

    /**
     * Move Files that are only for this one site over to new customer
     */
    protected function moveSiteFiles()
    {
        Log::debug('Checking for Customer Files that can be moved');

        /**
         * If this is the only site attached to customer, move all files
         */
        if ($this->isSolo) {
            $fileList = CustomerFile::where('cust_id', $this->moveSite->cust_id)
                ->whereNull('cust_equip_id')
                ->get();
            foreach ($fileList as $file) {
                $this->reAssignModel($file);
            }
        } else {
            $fileList = $this->moveSite->SiteFile;
            $this->cycleModel($fileList);
        }
    }

    /**
     * Re-assign site to new customer
     * If this is the only site attached to the customer, delete the customer
     */
    protected function finalize()
    {
        $customer = $this->moveSite->Customer;

        // If the site is the customers primary site, remove it
        if ($customer->primary_site_id === $this->moveSite->cust_site_id) {
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

        $this->moveSite->cust_id = $this->toCustomer->cust_id;
        $this->moveSite->save();
    }
}
