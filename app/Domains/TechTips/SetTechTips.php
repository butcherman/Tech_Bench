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
    public function processNewTip($request)
    {
        //  Determine if a file is being uploaded
        if(isset($request->file))
        {
            return $this->uploadFile($request);
        }

        return $this->createTip($request);
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
    protected function createTip($tipData)
    {
        $tip = TechTips::create([
            'tip_type_id' => $tipData->tip_type_id,
            'subject'     => $tipData->subject,
            'description' => $tipData->description,
            'user_id'     => Auth::user()->user_id,
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

    //  Send a notification of the new/edited tech tip
    protected function sendNotification($tip, $edit = false)
    {
        // $details = TechTips::find($tipID);
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
            abort(500);
        }

        return true;
    }
}
