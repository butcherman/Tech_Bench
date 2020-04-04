<?php

namespace App\Http\Controllers\FileLinks;

use App\Files;
use Carbon\Carbon;
use App\FileLinks;
use App\FileLinkFiles;
use App\CustomerFileTypes;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\FileLinksCollection;
use App\Http\Resources\CustomerFileTypesCollection;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use App\Http\Resources\FileLinks as FileLinksResource;

class FileLinksController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        //  Verify the user is logged in and has permissions for this page
        $this->middleware('auth');
        $this->middleware(function($request, $next) {
            $this->user = auth()->user();
            $this->authorize('hasAccess', 'Use File Links');
            return $next($request);
        });
    }

    //  Landing page shows all links that the user owns
    public function index()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return view('links.index');
    }

    //  Ajax call to show the links for a specific user
    public function find($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        //  Verify if the user is trying to pull their own links
        if($id == 0)
        {
            $id = Auth::user()->user_id;
        }
        //  If the user is trying to pull someone elses links, they must be able to manage users
        else if($id != Auth::user()->user_id)
        {
            $this->authorize('hasAccess', 'manage_users');
        }

        $links = new FileLinksCollection(
            FileLinks::where('user_id', $id)
                ->withCount('FileLinkFiles')
                ->orderBy('expire', 'desc')->get()
        );

        Log::debug('File links for User ID - '.$id.': ', array($links));

        return $links;
    }

    //  Create a new file link form
    public function create()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return view('links.newLink');
    }

    //  Submit the new file link form
    public function store(Request $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        $request->validate([
            'name'       => 'required',
            'expire'     => 'required',
            'customerID' => 'exists:customers,cust_id|nullable'
        ]);

        //  If there are files, process them first in their chunks
        if(!empty($request->file))
        {
            $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

            //  Recieve and process the file
            $save = $receiver->receive();

            //  See if the uploade has finished
            if($save->isFinished())
            {
                $this->saveFile($save->getFile());
                return response()->json(['upload_success' => true]);
            }

            //  Get the current progress if the file is not done being uploaded
            $handler = $save->handler();

            Log::debug('File being uploaded.  Percentage done - '.$handler->getPercentageDone());
            return response()->json([
                'done'   => $handler->getPercentageDone(),
                'status' => true
            ]);
        }

        // //  If there are no files being uploaded or the file uploade process is done
        $linkID = $this->createLink($request);
        if($request->session()->has('newLinkFile'))
        {
            //  If there were files uploaded to the link, process them
            $files = session('newLinkFile');
            $path = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$linkID;

            foreach($files as $file)
            {
                $data = Files::find($file);
                //  Move the file to the proper folder
                Storage::move($data->file_link.$data->file_name, $path.DIRECTORY_SEPARATOR.$data->file_name);
                //  Update file link in DB
                $data->update([
                    'file_link' => $path.DIRECTORY_SEPARATOR
                ]);
                //  Attach file to the link
                FileLinkFiles::create([
                    'link_id' => $linkID,
                    'file_id' => $data->file_id,
                    'user_id' => Auth::user()->user_id,
                    'upload' => 0
                ]);

                Log::info('File Added for link ID - '.$linkID.' by '.Auth::user()->full_name.'.  File ID-'.$data->file_id);
            }

            $request->session()->forget('newLinkFile');
        }

        return response()->json(['link' => $linkID, 'name' => Str::slug($request->name)]);
    }

    //  Create the new file link
    private function createLink($data)
    {
        //  Generate a random hash to use as the file link and make sure it is not already in use
        do
        {
            $hash = strtolower(Str::random(15));
            $dup  = FileLinks::where('link_hash', $hash)->get()->count();
            Log::debug('New possible link hash - '.$hash.'. Is duplicate -> '.$dup == 0 ? 'false' : 'true');
        } while($dup != 0);

        //  Create the new file link
        $link = FileLinks::create([
            'user_id'      => Auth::user()->user_id,
            'cust_id'      => $data->customerID,
            'link_hash'    => $hash,
            'link_name'    => $data->name,
            'expire'       => $data->expire,
            'allow_upload' => isset($data->allowUp) && $data->allowUp ? true : false,
            'note'         => $data->instructions
        ]);

        Log::info('File Link Created for '.Auth::user()->full_name.'.  Link Data - ', $link->toArray());

        return $link->link_id;
    }

    //  Save a file attached to the link
    private function saveFile(UploadedFile $file)
    {
        Log::debug('Preparing to save file. File Data - ', array($file));
        $filePath = config('filesystems.paths.links').DIRECTORY_SEPARATOR.'_tmp';
        Log:debug('File will be saved to '.$filePath);

        //  Clean the file and store it
        $fileName = Files::cleanFilename($filePath, $file->getClientOriginalName());
        $file->storeAs($filePath, $fileName);

        //  Place file in Files table of DB
        $newFile = Files::create([
            'file_name' => $fileName,
            'file_link' => $filePath.DIRECTORY_SEPARATOR
        ]);
        $fileID = $newFile->file_id;

        //  Save the file ID in the session array
        $fileArr = session('newLinkFile') != null ? session('newLinkFile') : [];
        $fileArr[] = $fileID;
        session(['newLinkFile' => $fileArr]);

        //  Log stored file
        Log::info('File Stored by '.Auth::user()->full_name, ['file_id' => $fileID, 'file_path' => $filePath.DIRECTORY_SEPARATOR, 'file_name' => $fileName]);
        return $fileID;
    }

    //  Show details about a file link
    public function details($id, /** @scrutinizer ignore-unused */ $name)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        //  Verify that the link is a valid link
        $linkData = FileLinks::find($id);
        $fileTypes = new CustomerFileTypesCollection(CustomerFileTypes::all());

        //  If the link is invalid, return an error page
        if(empty($linkData))
        {
            Log::warning('User '.Auth::user()->full_name.' tried to view bad file link', ['user_id' => Auth::user()->user_id, 'link_id' => $id]);
            return view('links.badLink');
        }

        return view('links.details', [
            'link_id'    => $linkData->link_id,
            'cust_id'    => $linkData->cust_id,
            'file_types' => $fileTypes
        ]);
    }











    //  Ajax call te get JSON details of the link
    public function show($id)
    {
        $linkData = new FileLinksResource(FileLinks::find($id));

        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return $linkData;
    }

    //  Update the link's details
    public function update(Request $request, $id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        $request->validate([
            'name'       => 'required',
            'expire'     => 'required',
            'customerTag' => 'exists:customers,cust_id|nullable'
        ]);

        FileLinks::find($id)->update([
            'link_name'    => $request->name,
            'expire'       => $request->expire,
            'allow_upload' => isset($request->allowUpload) && $request->allowUpload ? true : false,
            'cust_id'      => $request->customerTag,
            'note'         => $request->hasInstructions ? $request->instructions : null
        ]);

        Log::info('File Link Updated by '.Auth::user()->full_name, ['link_id' => $id]);

        return response()->json(['success' => true]);
    }

    //  Disable a file linke, but do not remove it (set the expire date to a previous date)
    public function disableLink($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        //  update the expire date for the link
        FileLinks::find($id)->update([
            'expire' => Carbon::yesterday()
        ]);

        Log::info('User '.Auth::user()->full_name.' disabled link ID - '.$id);
        return response()->json(['success' => true]);
    }

    //  Delete a file link
    public function destroy($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        //  Remove any files from database
        $data = FileLinkFiles::where('link_id', $id)->get();
        if(!$data->isEmpty())
        {
            foreach($data as $file)
            {
                $fileID = $file->file_id;
                $file->delete();
                Log::debug('File link file ID '.$file->file_id.' deleted from Link ID '.$id);

                //  Delete the file if it is no longer in use
                Files::deleteFile($fileID);
            }
        }

        FileLinks::find($id)->delete();

        Log::info('File link ID - '.$id.' deleted by '.Auth::user()->full_name);

        return response()->json([
            'success' => true
        ]);
    }
}
