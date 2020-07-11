<?php

namespace App\Domains\TechTips;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

use App\User;
use App\TechTips;
use App\TechTipFiles;
use App\TechTipSystems;

use App\Domains\Files\SetFiles;
use App\Notifications\NewTechTipNotification;
use App\Notifications\UpdateTechTipNotification;

class SetTechTips extends SetFiles
{
    //  Attach a single image that can be used in the html field of the tech tip body
    public function processImage($request)
    {
        //  Generate a unique hash as the file name and store it in a publicly accessable folder
        $path = 'images/tip_img';
        $location = Storage::disk('public')->putFile($path, new File($request->file));
        Log::info('Image uploaded for tech tip.  Image Data - ', $request->toArray());

        //  Return the full url path to the image
        return Storage::url($location);
    }

    //  Process the submitted data of a new tech tip - if a file chunk is sent, store that first
    public function processNewTip($request, $userID)
    {
        //  Determine if a file is being uploaded
        if(isset($request->file))
        {
            return $this->uploadFile($request);
        }

        return $this->createTip($request, $userID);
    }

    //  Edit an existing tech tips information
    public function processEditTip($request, $tipID, $userID)
    {
        //  Determine if a file is being uploaded
        if(isset($request->file))
        {
            return $this->uploadFile($request);
        }

        return $this->updateTip($request, $tipID, $userID);
    }

    //  Soft delete tech tip
    public function deactivateTip($tipID)
    {
        TechTips::find($tipID)->delete();
        return true;
    }

    //  Process the file chunk - if a full file is uploaded, set the file ID into session data
    protected function uploadFile($request)
    {
        $filename = $this->getChunk($request);
        if(!$filename)
        {
            return false;
        }

        $fileID    = $this->addDatabaseRow($filename, $this->path);
        $fileArr   = session('newTipFile') != null ? session('newTipFile') : [];
        $fileArr[] = $fileID;
        session(['newTipFile' => $fileArr]);

        return true;
    }

    //  Create a new Tech Tip
    protected function createTip($tipData, $userID)
    {
        $tip = TechTips::create([
            'tip_type_id' => $tipData->tip_type_id,
            'subject'     => $tipData->subject,
            'description' => $tipData->description,
            'user_id'     => $userID,
            'sticky'      => $tipData->sticky,
        ]);

        $this->processTipEquipment($tipData->equipment, $tip->tip_id);
        $this->processTipFiles($tip->tip_id);
        if(!$tipData->noEmail)
        {
            $this->sendNotification($tip);
        }

        return $tip->tip_id;
    }

    //  Update an existing Tech Tip
    protected function updateTip($tipData, $tipID, $userID)
    {
        TechTips::find($tipID)->update([
            'tip_type_id' => $tipData->tip_type_id,
            'subject'     => $tipData->subject,
            'description' => $tipData->description,
            'updated_id'  => $userID,
            'sticky'      => $tipData->sticky,
        ]);

        $this->checkTipEquipment($tipData->equipment, $tipID);
        $this->processTipFiles($tipID);
        if(isset($tipData->deletedFileList))
        {
            $this->removeTipFiles($tipData->deletedFileList, $tipID);
        }

        //  Send an updated notification if necessary
        if($tipData->notify)
        {
            $tip = TechTips::find($tipID);
            $this->sendNotification($tip, true);
        }

        return true;
    }

    //  Determine if any equipment types have been removed from the tip
    protected function checkTipEquipment($equipArr, $tipID)
    {
        $current  = TechTipSystems::where('tip_id', $tipID)->get();
        $newEquip = [];

        //  Cycle through all equipment to see if it is new or existing
        foreach($equipArr as $equip)
        {
            if(isset($equip['laravel_through_key']))
            {
                $current = $current->filter(function($item) use ($equip)
                {
                    return $item->sys_id != $equip['sys_id'];
                });
            }
            else
            {
                $newEquip[] = $equip;
            }
        }

        //  Remove anything left in the current field as it has been removed
        foreach($current as $cur)
        {
            TechTipSystems::find($cur->tip_tag_id)->delete();
        }

        //  Process all new equipment
        $this->processTipEquipment($newEquip, $tipID);
        return true;
    }

    //  Add any equipment types to the tech tip
    protected function processTipEquipment($equipArr, $tipID)
    {
        foreach($equipArr as $equip)
        {
            TechTipSystems::create([
                'tip_id' => $tipID,
                'sys_id' => $equip['sys_id'],
            ]);
        }

        return true;
    }

    //  Move any uploaded files from the default location, to the Tech Tip folder
    protected function processTipFiles($tipID)
    {
        $path = config('filesystems.paths.tips').DIRECTORY_SEPARATOR.$tipID.DIRECTORY_SEPARATOR;
        if(session('newTipFile') != null)
        {
            $fileArr = session('newTipFile');
            foreach($fileArr as $file)
            {
                $this->moveFile($file, $path);
                TechTipFiles::create([
                    'tip_id' => $tipID,
                    'file_id' => $file,
                ]);
            }

            session()->forget('newTipFile');
            return true;
        }

        return false;
    }

    //  Remove any files that are no longer needed for the tip
    protected function removeTipFiles($fileList, $tipID)
    {
        foreach($fileList as $file)
        {
            $details = TechTipFiles::find($file);
            $fileID  = $details->file_id;
            $details->delete();

            $this->deleteFile($fileID);
        }

        return true;
    }

    //  Send a notification of the new/edited tech tip
    protected function sendNotification($tip, $edit = false)
    {
        $users = User::whereHas('UserSettings', function($q)
        {
            $q->where('em_tech_tip', true);
        })->get();

        if(!$edit)
        {
            Notification::send($users, new NewTechTipNotification($tip));
        }
        else
        {
            Notification::send($users, new UpdateTechTipNotification($tip));
        }

        return true;
    }
}
