<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CustomerNoteService
{
    /**
     * Create a new Customer Note.
     */
    public function createCustomerNote(
        Collection $requestData,
        Customer $customer,
        User $user
    ): CustomerNote {
        $newNote = new CustomerNote(
            $requestData->except(['note_type', 'site_list'])->toArray()
        );

        $newNote->created_by = $user->user_id;

        $customer->Notes()->save($newNote);

        $this->processAssociations($requestData, $newNote);

        return $newNote;
    }

    /**
     * Update an existing Customer Note
     */
    public function updateCustomerNote(Collection $requestData, CustomerNote $note, User $user): CustomerNote
    {
        $note->update($requestData->except(['note_type', 'site_list'])
            ->toArray());

        $note->updated_by = $user->user_id;
        $note->save();

        $this->processAssociations($requestData, $note);

        return $note->fresh();
    }

    /**
     * Soft Delete/Force Delete a Customer Note
     */
    public function destroyCustomerNote(CustomerNote $note, ?bool $force = false): void
    {
        if ($force) {
            $note->forceDelete();

            return;
        }

        $note->delete();
    }

    /**
     * Restore a Soft Deleted Customer Note
     */
    public function restoreCustomerNote(CustomerNote $note): void
    {
        $note->restore();
    }

    /*
    |---------------------------------------------------------------------------
    | Add or Remove Sites/Equipment to the Customer Note
    |---------------------------------------------------------------------------
    */

    /**
     * Add the proper associated models and remove all others
     */
    protected function processAssociations(Collection $requestData, CustomerNote $note): void
    {
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

        $note->Sites()->sync([]);
    }

    /**
     * Associate a list of sites with the file and remove all other associations
     */
    protected function associateSites(CustomerNote $note, array $siteList): void
    {
        $note->Sites()->sync($siteList);

        $note->CustomerEquipment()->dissociate();
    }

    /**
     * Remove all file associations to make it a general file
     */
    protected function removeAssociations(CustomerNote $note): void
    {
        $note->CustomerEquipment()->dissociate();
        $note->Sites()->sync([]);
    }
}
