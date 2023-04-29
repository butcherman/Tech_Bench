<?php

namespace App\Traits;

use App\Models\TechTipEquipment;
use App\Models\TechTipFile;

trait TechTipTrait
{
    use FileTrait;

    /**
     * Add equipment types to a Tech Tip
     */
    protected function addEquipment($tipId, $equipList)
    {
        foreach ($equipList as $equip) {
            TechTipEquipment::create([
                'tip_id' => $tipId,
                'equip_id' => $equip['equip_id'],
            ]);
        }
    }

    /**
     * Find what equipment has been added or removed to the Tech Tip
     */
    protected function processUpdatedEquipment($tipId, $equipList)
    {
        $current = TechTipEquipment::where('tip_id', $tipId)->get();
        $new = [];

        //  Determine if the equipment is new or existing
        foreach ($equipList as $equip) {
            //  If the laravel_through_key value exists, then it was an existing equipment that has stayed in place
            if (isset($equip['laravel_through_key'])) {
                //  Remove that piece from the current equipment list so it is not updated later
                $current = $current->filter(function ($i) use ($equip) {
                    return $i->equip_id != $equip['equip_id'];
                });
            } else {
                $new[] = $equip;
            }
        }

        $this->processRemovedEquipment($current);
        $this->addEquipment($tipId, $new);
    }

    /**
     * Remove any equipment that is no longer associated with the Tech Tip
     */
    protected function processRemovedEquipment($removeList)
    {
        foreach ($removeList as $equip) {
            TechTipEquipment::find($equip->tip_equip_id)->delete();
        }
    }

    /**
     * Add files to the Tech Tip
     */
    protected function processNewFiles($tipId, $move = false)
    {
        $fileData = session()->pull('new-file-upload');
        if ($fileData) {
            foreach ($fileData as $file) {
                if ($move) {
                    $this->moveStoredFile($file->file_id, $tipId);
                }
                TechTipFile::create([
                    'tip_id' => $tipId,
                    'file_id' => $file->file_id,
                ]);
            }
        }
    }

    /**
     * Remove files from the Tech Tip
     */
    protected function removeFiles($tipId, $fileList)
    {
        foreach ($fileList as $file) {
            TechTipFile::where('tip_id', $tipId)->where('file_id', $file)->first()->delete();
            $this->deleteFile($file);
        }
    }
}
