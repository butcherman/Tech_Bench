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
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use App\Http\Resources\FileLinksCollection;

class FileLinksController extends Controller
{
    //  Only authorized users have access
    public function __construct(\Gate $gate)
    {
        //  Verify the user is logged in and has permissions for this page
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->authorize('hasAccess', 'use_file_links');
            return $next($request);
        });
    }

    //  Landing page shows all links that the user owns
    public function index()
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by User ID-' . Auth::user()->user_id);
        return view('links.index');
    }

    //  Ajax call to show the links for a specific user
    public function find($id)
    {
        if($id == 0)
        {
            $id = Auth::user()->user_id;
        }

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return $links = new FileLinksCollection(
            FileLinks::where('user_id', $id)
                ->withCount('FileLinkFiles')
                ->orderBy('expire', 'desc')->get()
        );
    }

    //  Create a new file link form
    public function create()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('links.newLink');
    }

    //  Submit the new file link form
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required',
            'expire' => 'required',
            'customerID' => 'exists:customers,cust_id|nullable'
        ]);

        if(!empty($request->file))
        {
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
                $this->saveFile($save->getFile());

                return 'uploaded successfully';
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

        //  If there are no files being uploaded or the file uploade process is done
        if(isset($request->_completed) && $request->_completed)
        {
            Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
            Log::debug('Link data submitted.  Data - ', $request->toArray());
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

                    Log::debug('File Added for link ID-'.$linkID.'.  File ID-'.$data->file_id);
                }

                $request->session()->forget('newLinkFile');
            }

            return response()->json(['link' => $linkID, 'name' => urlencode($request->name)]);
        }

        return response()->json(['complete' => false]);
    }

    //  Create the new file link
    private function createLink($data)
    {
        //  Generate a random hash to use as the file link and make sure it is not already in use
        do
        {
            $hash = strtolower(Str::random(15));
            $dup  = FileLinks::where('link_hash', $hash)->get()->count();
        } while($dup != 0);

        //  Create the new file link
        $link = FileLinks::create([
            'user_id'      => Auth::user()->user_id,
            'cust_id'      => $data->customerID,
            'link_hash'    => $hash,
            'link_name'    => $data->name,
            'expire'       => $data->expire,
            'allow_upload' => isset($data->allowUp) && $data->allowUp ? true : false
        ]);

        Log::info('File Link Created for User ID-'.Auth::user()->user_id.'.  Link ID-'.$link->link_id);

        return $link->link_id;
    }

    //  Save a file attached to the link
    private function saveFile(UploadedFile $file)
    {
        $filePath = config('filesystems.paths.links').DIRECTORY_SEPARATOR.'_tmp';

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
        Log::info('File Stored', ['file_id' => $fileID, 'file_path' => $filePath.DIRECTORY_SEPARATOR.$fileName]);
        return $fileID;
    }


















    //  Show details about a file link
    public function details($id, $name)
    {

        dd('blah');

        die();


        //  Verify that the link is a valid link
        $linkData = FileLinks::find($id);

        //  If the link is invalid, return an error page
        if(empty($linkData))
        {
            Log::warning('User tried to view bad file link', ['user_id' => Auth::user()->user_id, 'link_id' => $id]);
            return view('links.badLink');
        }

        //  Determine if the link has a customer attached or not
        $hasCust = $linkData->cust_id != null ? true : false;

        //  Get the possible file types for attaching a file to a customer
        $fileTypes = CustomerFileTypes::all();

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Link Detials - ', $linkData->toArray());
        return view('links.details', [
            'link_id'    => $id,
            'has_cust'   => $hasCust,
            'file_types' => $fileTypes
        ]);
    }

    //  Ajax call te get JSON details of the link
    public function show($id)
    {
        $linkData = FileLinks::where('link_id', $id)->leftJoin('customers', 'file_links.cust_id', '=', 'customers.cust_id')->first();

        //  Format the expiration date to be readable
        $linkData->timestamp = $linkData->expire;
        $linkData->expire    = date('M d, Y', strtotime($linkData->expire));

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Link Details - ', $linkData->toArray());
        return response()->json($linkData);
    }

    //  Update the link's details
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required',
            'expire' => 'required'
        ]);

        FileLinks::find($id)->update([
            'link_name'    => $request->name,
            'expire'       => $request->expire,
            'allow_upload' => isset($request->allowUp) && $request->allowUp ? true : false
        ]);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Updated Link Data for link ID-'.$id, $request->toArray());
        Log::info('File Link Updated', ['link_id' => $id]);

        return response()->json(['success' => true]);
    }

    //  Update the customer that is attached to the customer
    public function updateCustomer(Request $request, $id)
    {
        //  If the "customer id" field is populated, separate the ID from the name and prepare for insertion.
        if($request->customer_tag != null && $request->customer_tag != 'NULL')
        {
            $custID = explode(' ', $request->customer_tag);
            $custID = $custID[0];
        }
        else
        {
            $custID = null;
        }

        FileLinks::find($id)->update([
            'cust_id' => $custID
        ]);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data -', $request->toArray());
        return response()->json(['success' => true]);
    }




















    //  Disable a file linke, but do not remove it (set the expire date to a previous date)
    public function disableLink($id)
    {
        //  update the expire date for the link
        FileLinks::find($id)->update([
            'expire' => Carbon::yesterday()
        ]);

        Log::info('User ID '.Auth::user()->user_id.' disabled link ID - '.$id);
        Log::debug('Route ' . Route::currentRouteName() . ' visited by User ID-' . Auth::user()->user_id);
        return response()->json(['success' => true]);
    }

    //  Delete a file link
    public function destroy($id)
    {
        //  Remove the file from database
        $data = FileLinkFiles::where('link_id', $id)->get();
        if(!$data->isEmpty())
        {
            foreach($data as $file)
            {
                $fileID = $file->file_id;
                $file->delete();

                //  Delete the file if it is no longer in use
                Files::deleteFile($fileID);
            }
        }

        FileLinks::find($id)->delete();

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::info('File link deleted', ['link_id' => $id, 'user_id' => Auth::user()->user_id]);

        return response()->json([
            'success' => true
        ]);
    }
}
