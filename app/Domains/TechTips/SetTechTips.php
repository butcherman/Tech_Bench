<?php

namespace App\Domains\TechTips;

use Illuminate\Http\File;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

use App\Domains\FilesDomain;

use App\User;
use App\Files;
use App\TechTips;
use App\TechTipFavs;
use App\TechTipFiles;
use App\TechTipSystems;

use App\Http\Requests\TechTipNewTipRequest;
use App\Http\Requests\TechTipEditTipRequest;
use App\Http\Requests\TechTipProcessImageRequest;

use App\Notifications\NewTechTip;

class SetTechTips extends FilesDomain
{
    //  Store an image that has been uploaded to assign to a Tech Tip
    public function processTipImage(TechTipProcessImageRequest $request)
    {
        //  Generate a unique hash as the file name and store it in a publicly accessable folder
        $path = 'img/tip_img';
        $location = Storage::disk('public')->putFile($path, new File($request->file));
        Log::info('Image uploaded for tech tip.  Image Data - ', array($request));

        //  Return the full url path to the image
        return Storage::url($location);
    }

    //  Process the new tip form to see if a file is being uploaded, or upload is completed
    public function processNewTip(TechTipNewTipRequest $request)
    {
        if(isset($request->file))
        {
            $fileID = $this->processFileChunk($request);
            if($fileID)
            {
                $fileArr = session('newTipFile') != null ? session('newTipFile') : [];
                $fileArr[] = $fileID;
                session(['newTipFile' => $fileArr]);
            }

            return false;
        }

        return $this->createTip($request);
    }

    //  Update an existin gTech Tip
    public function processEditTip(TechTipEditTipRequest $request, $tipID)
    {
        if(isset($request->file))
        {
            $this->path = config('filesystems.paths.tips').DIRECTORY_SEPARATOR.$tipID;
            $fileID = $this->processFileChunk($request);
            if($fileID)
            {
                //  Attach file to Tech Tip
                TechTipFiles::create([
                    'tip_id' => $tipID,
                    'file_id' => $fileID,
                ]);
            }

            return false;
        }

        $this->updateTipDetails($request);
        $this->updateTipEquipment($request->system_types, $tipID);
        if(isset($request->deletedFileList))
        {
            $this->updateTipFiles($request->deletedFileList);
        }

        return true;
    }

    //  Delete the Tech Tip
    public function deleteTip($tipID)
    {
        TechTipFavs::where('tip_id', $tipID)->delete();

        //  Disable the tip
        TechTips::find($tipID)->delete();
        Log::warning('User - '.Auth::user()->full_name.' deleted Tech Tip ID - '.$tipID);

        return true;
    }

    //  Create the Tech Tip in the datbase
    protected function createTip($tipData)
    {
        //  Remove any forward slash (/) from the Subject Field
        $tipData->merge(['subject' => str_replace('/', '-', $tipData->subject)]);

        //  Enter the tip details and return the tip ID
        $tip = TechTips::create([
            'tip_type_id' => $tipData->tip_type_id,
            'subject'     => $tipData->subject,
            'description' => $tipData->description,
            'user_id'     => Auth::user()->user_id
        ]);
        $tipID = $tip->tip_id;

        $this->processTipEquipment($tipData->equipment, $tipID);
        $this->processFiles($tipID);
        $this->sendNotification($tipData->noEmail, $tipID);

        return $tipID;
    }

    //  Update the tip details
    protected function updateTipDetails($data)
    {
        //  Update the base information
        TechTips::find($data->tip_id)->update([
            'tip_type_id' => $data->tip_type_id,
            'subject'     => $data->subject,
            'description' => $data->description,
        ]);

        return true;
    }

    //  Add or remove any system types attached to the tip
    protected function updateTipEquipment($equipTypes, $tipID)
    {
        $current = TechTipSystems::where('tip_id', $tipID)->get();
        $newEquip = [];

        foreach($equipTypes as $equip)
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

        $this->processTipEquipment($newEquip, $tipID);

        foreach($current as $cur)
        {
            TechTipSystems::find($cur->tip_tag_id)->delete();
        }

        return true;
    }

    //  Remove any files that were deleted from the tip
    protected function updateTipFiles($deletedList)
    {
        foreach($deletedList as $delFile)
        {
            $details = TechTipFiles::find($delFile);
            $fileID = $details->file_id;
            $details->delete();

            $this->deleteFile($fileID);
        }
    }

    //  Store any equipment types that were attached to the tip
    protected function processTipEquipment($equipment, $tipID)
    {
        foreach($equipment as $sys)
        {
            TechTipSystems::create([
                'tip_id' => $tipID,
                'sys_id' => $sys['sys_id']
            ]);
        }

        return true;
    }

    //  For all files that were uploaded, move to the proper folder and attach to the tip
    protected function processFiles($tipID)
    {
        if(session('newTipFile') != null)
        {
            $files = session('newTipFile');
            $this->path = config('filesystems.paths.tips').DIRECTORY_SEPARATOR.$tipID;
            foreach($files as $file)
            {
                //  Move the file to the proper folder
                $data = Files::find($file);
                Storage::move($data->file_link.$data->file_name, $this->path.DIRECTORY_SEPARATOR.$data->file_name);
                $data->update([
                    'file_link' => $this->path.DIRECTORY_SEPARATOR
                ]);

                //  Attach file to Tech Tip
                TechTipFiles::create([
                    'tip_id' => $tipID,
                    'file_id' => $data->file_id
                ]);
            }

            session()->forget('newTipFile');
        }

        return true;
    }

    //  Send the notifications for the new tip
    protected function sendNotification($sendEmail, $tipID)
    {
        //  Send out the notifications
        if($sendEmail)
        {
            $details = TechTips::find($tipID);
            $users = User::whereHas('UserSettings', function($query) {
                $query->where('em_tech_tip', 1);
            })->get();

            Notification::send($users, new NewTechTip($details));
        }
    }
}
