<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\SystemTypes;
use App\SystemCategories;
use App\User;
use App\UserSettings;
use App\Files;
use App\TechTips;
use App\TechTipFiles;
use App\TechTipSystems;
use App\TechTipComments;
use App\TechTipFavs;
use App\Mail\NewTechtip;

class TechTipsController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Landing page brings up the tech tip search form
    public function index()
    {
        $systems = SystemCategories::with('SystemTypes')
            ->orderBy('cat_id', 'asc')
            ->get();
        
        $sysArr = [];
        foreach($systems as $sys)
        {
            foreach($sys->SystemTypes as $s)
            {
                $sysArr[$sys->name][$s->sys_id] = $s->name;
            }
        }
        
        return view('tip.index', [
            'systems' => $sysArr
        ]);
    }
    
    //  Search for a tech tip
    public function search(Request $request)
    {
        //  Run different request based on if system field is filled out or not
        if(!empty($request->system))
        {
            $tipData = TechTips::where('subject', 'like', '%'.$request->tipSearch.'%')
                ->whereHas('TechTipSystems', function($q) use($request)
                {
                   $q->where('sys_id', $request->system);
                })
                ->get();
        }
        else
        {
            $tipData = TechTips::where('subject', 'like', '%'.$request->tipSearch.'%')
                ->get();
        }

        return view('tip.searchResults', [
            'results' => $tipData
        ]);
    }

    //  Create a new Tech Tip
    public function create()
    {
        //  Get system types for tip tagging
        $systems = SystemCategories::with('SystemTypes')
            ->orderBy('cat_id', 'asc')
            ->get();
        
        $sysArr = [];
        foreach($systems as $sys)
        {
            foreach($sys->SystemTypes as $s)
            {
                $sysArr[$sys->name][$s->sys_id] = $s->name;
            }
        }
        
        return view('tip.form.newTip', [
            'systems' => $sysArr
        ]);
    }

    //  Submit the new tech tip
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required', 
            'details' => 'required', 
            'sysTags' => 'required'
        ]);
        
        //  Enter the tip details and get the tip ID
        $tip = TechTips::create([
            'subject'     => $request->subject,
            'description' => $request->details,
            'user_id'     => Auth::user()->user_id
        ]);
        $tipID = $tip->tip_id;

        //  Enter all system tags associated with the tip
        if(is_array($request->sysTags))
        {
            
            foreach($request->sysTags as $tag)
            {
                TechTipSystems::create([
                    'tip_id' => $tipID,
                    'sys_id' => $tag
                ]);
            }
        }
        else
        {
            TechTipSystems::create([
                'tip_id' => $tipID,
                'sys_id' => $request->sysTags
            ]);
        }
        
        //  If there are any files, process them
        if(!empty($request->file))
        {
            $filePath = config('filesystems.paths.tips').DIRECTORY_SEPARATOR.$tipID;
            foreach($request->file as $file)
            {
                //  Clean the file and store it
                $fileName = Files::cleanFilename($filePath, $file->getClientOriginalName());
                $file->storeAs($filePath, $fileName);
                
                //  Place file in Files table of DB
                $newFile = Files::create([
                    'file_name' => $fileName,
                    'file_link' => $filePath.DIRECTORY_SEPARATOR
                ]);
                $fileID = $newFile->file_id;
                
                //  Place the file in the tech tip files table of DB
                TechTipFiles::create([
                    'tip_id'  => $tipID,
                    'file_id' => $fileID
                ]);
            }
        }
        
        //  Email the techs of the new tip
        $tipData = TechTips::find($tipID);
        $userList = UserSettings::where('em_tech_tip', 1)->join('users', 'user_settings.user_id', '=', 'users.user_id')->where('active', 1)->get();
        try
        {
            Mail::to($userList)->send(new newTechtip($tipData));
        }
        catch(Exception $e)
        {
            report($e);
        }
        
        Log::info('Tech Tip ID-'.$tipID.' Created by Customer ID-'.Auth::user()->user_id);
        
        return $tipID;
    }

    //  Show the Tech Tip details
    public function details($id, $name)
    {
        $tipData = TechTips::where('tip_id', $id)->with('user')->first();
        if(empty($tipData))
        {
            Log::warning('User ID-'.Auth::user()->user_id.' tried to access invlaid Tech Tip ID-'.$id.' Name-'.$name);
            return view('errors.tipNotFound');
        }
        
        $tipFiles = TechTipFiles::where('tip_id', $id)
            ->join('files', 'tech_tip_files.file_id', '=', 'files.file_id')
            ->get();
        $tipCmts  = TechTipComments::where('tip_id', $id)
            ->get();
        $tipSys   = TechTipSystems::where('tip_id', $id)
            ->join('system_types', 'tech_tip_systems.sys_id', '=', 'system_types.sys_id')
            ->get();
        $tipFav   = TechTipFavs::where('user_id', Auth::user()->user_id)
            ->where('tip_id', $id)
            ->first();
        
        return view('tip.details', [
            'data'     => $tipData,
            'files'    => $tipFiles,
            'systems'  => $tipSys,
            'comments' => $tipCmts,
            'isFav'    => $tipFav
        ]);
    }
    
    //  Toggle whether or not the customer is listed as a user favorite
    public function toggleFav($action, $tipID)
    {
        switch ($action)
        {
            case 'add':
                TechTipFavs::create([
                    'user_id' => Auth::user()->user_id,
                    'tip_id'  => $tipID
                ]);
                break;
            case 'remove':
                $tipFav = TechTipFavs::where('user_id', Auth::user()->user_id)->where('tip_id', $tipID)->first();
                $tipFav->delete();
                break;
        }        
    }

    //  Edit a Tech Tip
    public function edit($id)
    {
        $tipData = TechTips::find($id);
        if(empty($tipData))
        {
            return 'tip not found';
        }
        
        $tipFiles = TechTipFiles::where('tip_id', $id)
            ->join('files', 'tech_tip_files.file_id', '=', 'files.file_id')
            ->get();
        $tipCmts  = TechTipComments::where('tip_id', $id)
            ->get();
        $tipSys   = TechTipSystems::where('tip_id', $id)
            ->join('system_types', 'tech_tip_systems.sys_id', '=', 'system_types.sys_id')
            ->get();
        $tipFav   = TechTipFavs::where('user_id', Auth::user()->user_id)
            ->where('tip_id', $id)
            ->first();
        
        //  Get system types for tip tagging
        $systems = SystemCategories::with('SystemTypes')
            ->orderBy('cat_id', 'asc')
            ->get();
        
        $sysArr = [];
        foreach($systems as $sys)
        {
            foreach($sys->SystemTypes as $s)
            {
                $sysArr[$sys->name][$s->sys_id] = $s->name;
            }
        }
        
        return view('tip.form.editTip', [
            'data'     => $tipData,
            'files'    => $tipFiles,
            'systems'  => $tipSys,
            'comments' => $tipCmts,
            'isFav'    => $tipFav,
            'sysToTag' => $sysArr
        ]);
    }

    //  Update the tech tip
    public function update(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required', 
            'details' => 'required', 
            'sysTags' => 'required'
        ]);
        
        //  update tip details
        TechTips::find($id)->update([
            'subject'     => $request->subject,
            'description' => $request->description
        ]);
        
        //  Enter all system tags associated with the tip after destroying the existing systems
        TechTipSystems::where('tip_id', $id)->delete();
        if(is_array($request->sysTags))
        {
            foreach($request->sysTags as $tag)
            {
                TechTipSystems::create([
                    'tip_id' => $id,
                    'sys_id' => $tag
                ]);
            }
        }
        else
        {
            TechTipSystems::create([
                'tip_id' => $id,
                'sys_id' => $request->sysTags
            ]);
        }
        
        //  Determine if any files were removed
        $tipFiles = TechTipFiles::where('tip_id', $id)->get();
        if(!$tipFiles->isEmpty())
        {
            if(!empty($request->existingFile))
            {
                foreach($tipFiles as $file)
                {
                    if(!in_array($file->file_id, $request->existingFile))
                    {
                        TechTipFiles::where('file_id', $file->file_id)->delete();
                        Files::deleteFile($file->file_id);
                    }
                }
            }
            else
            {
                TechTipFiles::where('tip_id', $id)->delete();
            }
        }
        
        //  Process any new files
        if(!empty($request->file))
        {
            $filePath = config('filesystems.paths.tips').DIRECTORY_SEPARATOR.$id;
            foreach($request->file as $file)
            {
                //  Clean the file and store it
                $fileName = Files::cleanFilename($filePath, $file->getClientOriginalName());
                $file->storeAs($filePath, $fileName);
                
                //  Place file in Files table of DB
                $newFile = Files::create([
                    'file_name' => $fileName,
                    'file_link' => $filePath.DIRECTORY_SEPARATOR
                ]);
                $fileID = $newFile->file_id;
                
                //  Place the file in the tech tip files table of DB
                TechTipFiles::create([
                    'tip_id'  => $id,
                    'file_id' => $fileID
                ]);
            }
        }
        
        Log::info('Tech Tip ID-'.$id.' Updated by User ID-'.Auth::user()->user_id);
        
        return $id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
