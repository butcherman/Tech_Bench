<?php

namespace App\Http\Controllers;

//use Zip;
//use App\Files;
use App\FileLinks;
//use App\Customers;
//use App\CustomerFiles;
//use App\FileLinkFiles;
//use App\FileLinkNotes;
use Illuminate\Http\Request;
//use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Storage;

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

    //  Create a new file link form
    public function create()
    {
        return view('links.form.newLink');
    }

    //  Submit the new file link form
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required', 
            'expire' => 'required'
        ]);
        
        //  Generate a random hash to use as the file link and make sure it is not already in use
        do
        {
            $hash = strtolower(str_random(15));
            $dup  = FileLinks::where('link_hash', $hash)->get()->count();
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
//        if(!empty($request->file))
//        {
//            $filePath = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$linkID;
//            foreach($request->file as $file)
//            {
//                //  Clean the file and store it
//                $fileName = Files::cleanFilename($filePath, $file->getClientOriginalName());
//                $file->storeAs($filePath, $fileName);
//                
//                //  Place file in Files table of DB
//                $newFile = Files::create([
//                    'file_name' => $fileName,
//                    'file_link' => $filePath.DIRECTORY_SEPARATOR
//                ]);
//                $fileID = $newFile->file_id;
//                
//                //  Place the file in the file link files table of DB
//                FileLinkFiles::create([
//                    'link_id'  => $linkID,
//                    'file_id'  => $fileID,
//                    'user_id'  => Auth::user()->user_id,
//                    'upload'   => 0
//                ]);
//                
//                //  Log stored file
//                Log::info('File Stored', ['file_id' => $fileID, 'file_path' => $filePath.DIRECTORY_SEPARATOR.$fileName]);
//            }
//        }
        
        Log::info('File Link Created', ['link_id' => $linkID, 'user_id' => Auth::user()->user_id]);
        
        return urlencode($linkID);
        
        
        
        
        
        
        
        
        
        
        
        
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
