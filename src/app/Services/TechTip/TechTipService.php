<?php

namespace App\Services\TechTip;

use App\Enums\CrudAction;
use App\Events\TechTip\NotifiableTechTipEvent;
use App\Jobs\TechTip\ProcessTipFilesJob;
use App\Models\TechTip;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TechTipService
{
    /**
     * Create a new Tech Tip and attach all Equipment and Files.
     */
    public function createTechTip(Collection $requestData, User $user, array $fileList): TechTip
    {
        $newTip = new TechTip(
            $requestData->except(['equipList', 'suppress'])->toArray()
        );

        // Attach user and generate a unique slug
        $newTip->CreatedBy()->associate($user);
        $newTip->slug = $this->generateSlug($requestData->get('subject'));
        $newTip->save();

        // Attach Equipment
        $newTip->Equipment()->sync($requestData->get('equipList'));

        // Attach Files
        if (count($fileList)) {
            $newTip->Files()->attach($fileList);
            ProcessTipFilesJob::dispatch($newTip);
        }

        // Send out notifications if needed
        if (! $requestData->get('suppress')) {
            NotifiableTechTipEvent::dispatch($newTip, $user, CrudAction::Create);
        }

        return $newTip;
    }

    /**
     * Update an existing Tech Tip and attach all Equipment and Files.
     */
    // public function updateTechTip(
    //     Collection $requestData,
    //     TechTip $techTip,
    //     User $user,
    //     array $fileList): TechTip
    // {
    //     $techTip->update(
    //         $requestData->except(['equipList', 'suppress', 'removedFiles'])
    //             ->toArray()
    //     );

    //     // Note the user that updated the tip
    //     $techTip->updated_id = $user->user_id;

    //     // If the Subject has changed, we need to update the Slug for the URL.
    //     $techTip->slug = $this->generateSlug($requestData->get('subject'));

    //     $techTip->save();

    //     // Attach Equipment and Files
    //     $techTip->EquipmentType()->sync($requestData->get('equipList'));
    //     $techTip->FileUpload()->attach($fileList);
    //     $techTip->FileUpload()->detach($requestData->get('removedFiles'));

    //     if (count($fileList)) {
    //         MoveTmpFilesJob::dispatch($fileList, $techTip->tip_id);
    //     }

    //     // Refresh Model
    //     $techTip->fresh();

    //     // Send out notifications if needed
    //     if (! $requestData->get('suppress')) {
    //         event(
    //             new NotifiableTechTipEvent($techTip, $user, CrudAction::Update)
    //         );
    //     }

    //     return $techTip;
    // }

    /**
     * Delete a Tech Tip
     */
    public function destroyTechTip(TechTip $techTip, ?bool $force = false): void
    {
        if ($force) {
            $techTip->forceDelete();

            return;
        }

        $techTip->delete();
    }

    /**
     * Restore a Tech Tip.
     */
    // public function restoreTechTip(TechTip $techTip): void
    // {
    //     $techTip->restore();
    // }

    /**
     * Generate a unique slug to reference in the Tech Tip URL
     */
    protected function generateSlug(string $subject): string
    {
        $index = 1;
        $slug = Str::slug($subject);

        while (TechTip::where('slug', $slug)->first()) {
            $slug = Str::slug($subject) . '-' . $index;
            $index++;
        }

        return $slug;
    }
}
