<?php

namespace App\Http\Controllers\Systems;

use App\Files;
use App\SystemTypes;
use App\SystemFiles;
use App\SystemFileTypes;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

class SystemFilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Store a new system file
    public function store(Request $request)
    {
        //  Validate the form
        $request->validate([
            'name'  => 'required',
            'sysID' => 'required',
            'type'  => 'required',
            'file'  => 'required'
        ]);
        
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        
        //  Verify that the upload is valid and being processed
        if($receiver->isUploaded() === false)
        {
            Log::error('Upload File Missing - '.$request->toArray());
            throw new UploadMissingFileException();
        }
        
            //  Recieve and process the file
        $save = $receiver->receive();

        //  See if the uploade has finished
        if($save->isFinished())
        {
            
            //  Get the system information
            $sys = SystemTypes::where('sys_id', $request->sysID)
                ->join('system_categories', 'system_types.cat_id', '=', 'system_categories.cat_id')
                ->select('system_types.*', 'system_categories.name AS cat_name')
                ->first();

            //  Set the file location and clean the file name for storage
            $filePath = config('filesystems.paths.systems')
                .DIRECTORY_SEPARATOR.strtolower($sys->cat_name)
                .DIRECTORY_SEPARATOR.$sys->folder_location;

            $fileName = Files::cleanFileName($filePath, $request->file->getClientOriginalName());

            //  Store the file and place it in the database
            $request->file->storeAs($filePath, $fileName);
            $file = Files::create([
                'file_name' => $fileName,
                'file_link' => $filePath.DIRECTORY_SEPARATOR
            ]);

            //  Get the information for the system files table
            $fileID = $file->file_id;
            $typeID = SystemFileTypes::where('description', $request->type)->first()->type_id;

            //  Attach file to system type
            SystemFiles::create([
                'sys_id'      => $request->sysID,
                'type_id'     => $typeID,
                'file_id'     => $fileID,
                'name'        => $request->name,
                'description' => $request->description,
                'user_id'     => Auth::user()->user_id
            ]);

            Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
            Log::debug('Submitted Data - ', $request->toArray());
            Log::info('File ID-'.$fileID.' Added For System ID-'.$request->sysID.' By User ID-'.Auth::user()->user_id);

            return response()->json(['success' => true]);
        }

        //  Get the current progress
        $handler = $save->handler();

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('File being uploaded.  Percentage done - '.$handler->getPercentageDone());
        return response()->json([
            'done'   => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    //  Return JSON array of the system files
    public function show($id)
    {
        $fileTypes = SystemFileTypes::all();        
        $files = SystemFiles::where('sys_id', $id)
            ->select('system_files.sys_id', 'system_files.type_id', 'system_files.name', 'system_files.description', 'users.first_name', 'users.last_name', 'system_files.created_at')
            ->join('files', 'system_files.file_id', '=', 'files.file_id')
            ->join('users', 'system_files.user_id', '=', 'users.user_id')
            ->get();
        
        $i = 0;
        $fileArr = [];
        foreach($fileTypes as $type)
        {
            $fileArr[$i] = [
                'description' => $type->description,
                'data'        => false
            ];
                
            foreach($files as $file)
            {
                if($file->type_id == $type->type_id)
                {
                    $fileArr[$i]['data'][] = [
                        'id'          => $file->sys_file_id,
                        'url'         => route('download', [$file->file_id, $file->file_name]),
                        'name'        => $file->name,
                        'description' => $file->description,
                        'user'        => $file->first_name.' '.$file->last_name,
                        'added'       => date('M d, Y', strtotime($file->created_at))
                    ];
                }
            }
            $i++;
        }

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Fetched Data - ', $fileArr);
        return response()->json($fileArr);
    }

    //  Update the file information
    public function update(Request $request, $id)
    {
        SystemFiles::find($id)->update([
            'name'        => $request->name,
            'description' => $request->desc
        ]);
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::info('File Information on System File ID-'.$id.' Updated by User ID-'.Auth::user()->user_id);
        
        return response()->json(['success' => true]);
    }
    
    //  Replace the file with a new one
    public function replace(Request $request)
    {
        //  Validate the form
        $request->validate(['file' => 'required']);
        
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
            
        //  Verify that the upload is valid and being processed
        if($receiver->isUploaded() === false)
        {
            Log::error('Upload File Missing - '.$request->toArray());
            throw new UploadMissingFileException();
        }

        //  Recieve and process the file
        $save = $receiver->receive();

        //  See if the uploade has finished
        if($save->isFinished())
        {
            //  Get the original file information
            $origData = SystemFiles::find($request->fileID)->with('files')->first();
            $filePath = $origData->files->file_link;
            //  Clean the filename
            $fileName = Files::cleanFileName($filePath, $request->file->getClientOriginalName());

            //  Store the file
            $request->file->storeAs($filePath, $fileName);

            //  Put the file in the database
            $file = Files::create(
            [
                'file_name' => $fileName,
                'file_link' => $filePath.DIRECTORY_SEPARATOR
            ]);

            //  Get information for system files table
            $fileID = $file->file_id;

            SystemFiles::find($request->fileID)->update([
                'file_id' => $fileID
            ]);   

            Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
            Log::debug('Submitted Data - ', $request->toArray());
            Log::info('System File ID-'.$fileID.' Replaced by User ID-'.Auth::user()->user_id);

            return response()->json(['success' => true]);
        }

        //  Get the current progress
        $handler = $save->handler();

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('File being uploaded.  Percentage done - '.$handler->getPercentageDone());
        return response()->json([
            'done'   => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    //  Delete the system file
    public function destroy($id)
    {
        //  Remove the file from SystemFiles table
        $data = SystemFiles::find($id);
        $fileID = $data->file_id;
        
        Log::info('System File ID-'.$fileID.' Deleted For System ID-'.$data->sys_id.' by User ID-'.Auth::user()->user_id);
        
        $data->delete();
        
        //  Delete from system if no longer in use
        Files::deleteFile($fileID);
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('File Data - ', $data->toArray());
        Log::notice('File ID-'.$data->file_id.' deleted by user ID-'.Auth::user()->user_id);
        return response()->json(['success' => true]);
    }
}
