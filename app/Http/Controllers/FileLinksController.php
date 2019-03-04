<?php

namespace App\Http\Controllers;

use Zip;
use App\Files;
use App\FileLinks;
use App\Customers;
use App\CustomerFiles;
use App\FileLinkFiles;
use App\FileLinkNotes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileLinksController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Landing page shows all links that the user owns
    public function index()
    {
        return view('links.index');
    }

    //  Create a new File Link
    public function create()
    {
        return view('links.form.newLink');
    }

    //  Store the new file link
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required', 
            'expire' => 'required'
        ]);
        
        //  Generate a random hash to use as the file link and make sure it is not already in use
        do
        {
            $hash = strtolower(str_random(15));
            $dup = FileLinks::where('link_hash', $hash)->get()->count();
        }while($dup != 0);
        
        //  If the "customer id" field is populated, separate the ID from the name and prepare for insertion.
        if($request->customer_tag != null)
        {
            $custID = explode(' ', $request->customer_tag);
            $custID = $custID[0];
        }
        else
        {
            $custID = null;
        }
                
        //  Create the new file link
        $link = FileLinks::create([
            'user_id'      => Auth::user()->user_id,
            'cust_id'      => $custID,
            'link_hash'    => $hash,
            'link_name'    => $request->name,
            'expire'       => $request->expire,
            'allow_upload' => isset($request->allowUp) && $request->allowUp ? true : false
        ]);
        $linkID = $link->link_id;
        
        //  If there are any files, process them
        if(!empty($request->file))
        {
            $filePath = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$linkID;
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
                
                //  Place the file in the file link files table of DB
                FileLinkFiles::create([
                    'link_id'  => $linkID,
                    'file_id'  => $fileID,
                    'user_id'  => Auth::user()->user_id,
                    'upload'   => 0
                ]);
                
                //  Log stored file
                Log::info('File Stored', ['file_id' => $fileID, 'file_path' => $filePath.DIRECTORY_SEPARATOR.$fileName]);
            }
        }
        
        Log::info('File Link Created', ['link_id' => $linkID, 'user_id' => Auth::user()->user_id]);
        
        return urlencode($linkID);
    }
    
    //  Load the form to attach link to a customer
    public function attachCustForm($id)
    {
        $custData = FileLinks::find($id);  
        
        if($custData->cust_id != null)
        {
            $custName = Customers::find($custData->cust_id)->name;
            $custName = $custData->cust_id.' - '.$custName;
        }
        else
        {
            $custName = '';
        }
        
        return view('links.form.linkCust', [
            'cust' => $custName
        ]);
    }
    
    //  Update the customer ID that is attached to the link
    public function submitCustForm(Request $request, $id)
    {
        if($request->customer_tag)
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

        return 'success';
    }

    //  Show file links for a specific user
    public function show($id)
    {
        $links = FileLinks::where('user_id', $id)
            ->withCount('FileLinkFiles')
            ->orderBy('expire', 'desc')
            ->get();
        
        return view('links.loadLinks', [
            'links' => $links
        ]);
    }
    
    //  Show a links information
    public function details($id, $name)
    {
        $linkData = FileLinks::find($id);
        
        //  If the link is invalid, return an error page
        if(empty($linkData))
        {
            return view('links.badLink');
        }
        
        //  Email information for the "Email Link" button
        $emailMsg = "mailto:?subject=A File Link Has Been Created For You&body=View the link details here:  ".route('userLink.details', ['link' => $linkData->link_hash]);
        
        //  Determine if the link is attached to a customer
        $custName = Customers::find($linkData->cust_id);
        $custName = $custName != null ? $custName->name : '';
        
        return view('links.details', [
            'data'  => $linkData,
            'emMsg' => $emailMsg,
            'cust'  => $custName
        ]);
    }
    
    //  Get the files for the link
    public function getFiles($type, $linkID)
    {
        $files = null;
        //  Show files
        switch($type)
        {
            //  For files that are available for a guest to download
            case 'down':
                $files = FileLInkFiles::where('link_id', $linkID)
                    ->where('upload', false)
                    ->join('files', 'file_link_files.file_id', '=', 'files.file_id')
                    ->get();
                break;
            //  For files that are uploaded by a guest
            case 'up':
                $files = Files::where('file_link_files.link_id', $linkID)
                    ->where('file_link_files.upload', true)
                    ->join('file_link_files', 'files.file_id', '=', 'file_link_files.file_id')
                    ->with('FileLinkNotes')
                    ->get();
                break;
        }
        
        return view('links.fileList', [
            'files'  => $files,
            'type'   => $type,
            'linkID' => $linkID
        ]);
    }
    
    //  Add a new file form
    public function addFileForm($id)
    {
        return view('links.form.addFile', [
            'id' => $id
        ]);
    }
    
    //  Submit the additional files form
    public function submitAddFile($id, Request $request)
    {
        $filePath = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$id;
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

            //  Place the file in the file link files table of DB
            FileLinkFiles::create([
                'link_id'  => $id,
                'file_id'  => $fileID,
                'user_id'  => Auth::user()->user_id,
                'upload'   => 0
            ]);
            
            //  Log stored file
            Log::info('File Stored', ['file_id' => $fileID, 'file_path' => $filePath.DIRECTORY_SEPARATOR.$fileName]);
        }
    }
    
    //  Move a file to a customer
    public function moveFile(Request $request)
    {
        $request->validate([
            'file_id'      => 'required',
            'cust_id'      => 'required',
            'name'         => 'required',
            'file_type_id' => 'required'
        ]);
        
        $newPath = config('filesystems.paths.customers').DIRECTORY_SEPARATOR.$request->cust_id;
        $fileID = FileLinkFiles::find($request->file_id)->file_id;
        $fileData = Files::find($fileID);
        
        //  Verify the file does not already exist in the customer data
        $dup = CustomerFiles::where('file_id', $fileID)->where('cust_id', $request->cust_id)->count();
        if($dup)
        {
            return 'duplicate';
        }
        
        //  Move the file to the customers file folder
        Storage::move($fileData->file_link.$fileData->file_name, $newPath.$fileData->file_name);
        
        //  Update file database
        $fileData->update([
            'file_link' => $newPath
        ]);
        
        //  Place file in customer database
        CustomerFiles::create([
            'file_id'      => $fileID,
            'file_type_id' => $request->file_type_id,
            'cust_id'      => $request->cust_id,
            'user_id'      => Auth::user()->user_id,
            'name'         => $request->name
        ]);
        
        //  Determine if the file should be removed from the file link
        if(isset($request->remove) && $request->remove == 'on')
        {
            FileLinkFiles::find($request->file_id)->delete();
        }
        
        return 'success';
    }
    
    //  Get a note that is attached to a file
    public function getNote($id)
    {
        $note = FileLinkNotes::find($id);
        
        return $note->note;
    }
    
    //  Download all files uploaded to a link in a zip format
    public function downloadAllFiles($linkID)
    {
        $files = Files::where('file_link_files.link_id', $linkID)
            ->where('file_link_files.upload', true)
            ->join('file_link_files', 'files.file_id', '=', 'file_link_files.file_id')
            ->with('FileLinkNotes')
            ->get();
        $path = config('filesystems.disks.local.root').DIRECTORY_SEPARATOR;
        
        $zip = Zip::create($path.'download.zip');
        foreach($files as $file)
        {
            $zip->add($path.$file->file_link.$file->file_name);
        }
        
        $zip->close();
        
        return response()->download($path.'download.zip')->deleteFileAfterSend(true);        
    }

    //  Edit a links basic informaiton
    public function edit($id)
    {
        $linkData = FileLinks::find($id);
        
        return view('links.form.editLink', [
            'data' => $linkData
        ]);
    }
    
    //  Open the instructions form
    public function instructionsForm($id)
    {
        $linkNote = FileLinks::find($id)->note;
        
        return view('links.form.instructions', [
            'id'   => $id,
            'note' => $linkNote
        ]);
    }
    
    //  Submit the instructions form
    public function submitInstructions(Request $request, $id)
    {
        FileLinks::find($id)->update([
            'note' => $request->instructions
        ]);
        
        return 'success';
    }

    //  Submit the edit link form
    public function update(Request $request, $id)
    {
        $request->validate([
            'link_name' => 'required', 
            'expire'    => 'required'
        ]);
        
        FileLinks::find($id)->update([
            'link_name'    => $request->link_name,
            'expire'       => $request->expire,
            'allow_upload' => isset($request->allowUp) && $request->allowUp ? true : false
        ]);
        
        Log::info('File Link Updated', ['link_id' => $id]);
    }
    
    //  Delete a file attached to a link
    public function deleteLinkFile($linkFileID)
    {
        $fileData = FileLinkFiles::find($linkFileID);
        $fileID = $fileData->file_id;
        $fileData->delete();
        
        Files::deleteFile($fileID);
    }

    //  Delete a link
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
        
        Log::info('File link deleted', ['link_id' => $id, 'user_id' => Auth::user()->user_id]);
    }
}
