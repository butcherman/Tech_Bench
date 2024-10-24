<?php

namespace App\Service\Customer;

use App\Http\Requests\Customer\CustomerNoteRequest;
use App\Models\Customer;
use App\Models\CustomerNote;

class CustomerNoteService
{
    public function createCustomerNote(
        CustomerNoteRequest $requestData,
        Customer $customer
    ): CustomerNote {
        $newNote = new CustomerNote(
            $requestData->except(['note_type', 'site_list'])
        );
        $newNote->created_by = $requestData->user()->user_id;

        $customer->CustomerNote()->save($newNote);

        $this->processAssociations($requestData, $newNote);

        return $newNote;
    }

    public function updateCustomerNote(
        CustomerNoteRequest $requestData,
        CustomerNote $note
    ): CustomerNote {
        $requestData->merge(['updated_by' => $requestData->user()->user_id]);
        $note->update($requestData->except(['note_type', 'site_list']));

        $this->processAssociations($requestData, $note);

        return $note;
    }

    public function destroyCustomerNote(CustomerNote $note, ?bool $force = false): void
    {
        if ($force) {
            $note->forceDelete();

            return;
        }

        $note->delete();
    }

    public function restoreCustomerNote(CustomerNote $note): void
    {
        $note->restore();
    }

    /**
     * Add the proper associated models and remove all others
     */
    protected function processAssociations(
        CustomerNoteRequest $requestData,
        CustomerNote $note
    ): void {
        match ($requestData->note_type) {
            'equipment' => $this->associateEquipment(
                $note,
                $requestData->cust_equip_id
            ),
            'site' => $this->associateSites($note, $requestData->site_list),
            default => $this->removeAssociations($note),
        };
    }

    /**
     * Associate an equipment id with the file, and remove all other associations
     */
    protected function associateEquipment(CustomerNote $note, int $equipId): void
    {
        $note->cust_equip_id = $equipId;
        $note->save();

        $note->CustomerSite()->sync([]);
    }

    /**
     * Associate a list of sites with the file and remove all other associations
     */
    protected function associateSites(CustomerNote $note, array $siteList): void
    {
        $note->CustomerSite()->sync($siteList);

        $note->CustomerEquipment()->dissociate();
    }

    /**
     * Remove all file associations to make it a general file
     */
    protected function removeAssociations(CustomerNote $note): void
    {
        $note->CustomerEquipment()->dissociate();
        $note->CustomerSite()->sync([]);
    }
}
