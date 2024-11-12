<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;
use Illuminate\Support\Collection;

class CustomerNoteService
{
    public function createCustomerNote(
        Collection $requestData,
        Customer $customer,
        User $user
    ): CustomerNote {
        $newNote = new CustomerNote(
            $requestData->except(['note_type', 'site_list'])->toArray()
        );

        $newNote->created_by = $user->user_id;

        $customer->CustomerNote()->save($newNote);

        $this->processAssociations($requestData, $newNote);

        return $newNote;
    }

    public function updateCustomerNote(
        Collection $requestData,
        CustomerNote $note,
        User $user
    ): CustomerNote {
        $requestData->merge(['updated_by' => $user->user_id]);
        $note->update($requestData->except(['note_type', 'site_list'])
            ->toArray());

        $this->processAssociations($requestData, $note);

        return $note->fresh();
    }

    public function destroyCustomerNote(
        CustomerNote $note,
        ?bool $force = false
    ): void {
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
        Collection $requestData,
        CustomerNote $note
    ): void {
        match ($requestData->get('note_type')) {
            'equipment' => $this->associateEquipment(
                $note,
                $requestData->get('cust_equip_id')
            ),
            'site' => $this->associateSites($note, $requestData->get('site_list')),
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
