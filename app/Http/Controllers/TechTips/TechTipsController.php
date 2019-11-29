<?php

namespace App\Http\Controllers\TechTips;

use App\User;
use App\Files;
use App\TechTips;
use App\SystemTypes;
use App\SystemFiles;
use App\TechTipFiles;
use App\TechTipSystems;
use App\SystemFileTypes;
use App\SystemCategories;
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
use Illuminate\Http\File;

// use App\Http\Resources\TechTipTypes;
use App\Http\Resources\TechTipTypesCollection;
use App\TechTipTypes;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewTechTip;

use App\Http\Resources\SystemCategoriesCollection as CategoriesCollection;
use App\Http\Resources\SystemCategoriesCollection;
use App\Http\Resources\SystemTypesCollection;
// use App\SystemCategories;
// use App\SystemTypes;

use App\Http\Resources\TechTipsCollection;
use Illuminate\Support\Facades\Storage;

class TechTipsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Tech Tips landing page
    public function index()
    {
        $tipTypes = new TechTipTypesCollection(TechTipTypes::all());
        $sysList  = new CategoriesCollection(SystemCategories::with('SystemTypes')->with('SystemTypes.SystemDataFields.SystemDataFieldTypes')->get());
        return view('tips.index', [
            'tipTypes' => $tipTypes,
            'sysTypes' => $sysList,
            'canCreate' => $this->authorize('hasAccess', 'create_tech_tip') ? true : false
        ]);
    }

    //  Search for an existing tip - If no paramaters, return all tips
    public function search(Request $request)
    {
        Log::debug('request Data -> ', $request->toArray());

        //  See if there are any search paramaters entered
        if (!$request->search['searchText'] && !isset($request->search['articleType']) && !isset($request->search['systemType'])) {
            //  No search paramaters, send all tech tips
            $tips = new TechTipsCollection(
                TechTips::orderBy('created_at', 'DESC')
                    ->with('SystemTypes')
                    ->paginate($request->pagination['perPage'])
            );
        } else {
            $article = isset($request->search['articleType']) ? true : false;
            $system  = isset($request->search['systemType'])  ? true : false;
            //  Search paramaters, filter results
            $tips = new TechTipsCollection(
                TechTips::orderBy('created_at', 'DESC')
                    //  Search by id or a phrase in the title or description
                    ->where(function ($query) use ($request) {
                        $query->where('subject', 'like', '%' . $request->search['searchText'] . '%')
                            ->orWhere('tip_id', 'like', '%' . $request->search['searchText'] . '%')
                            ->orWhere('description', 'like', '%' . $request->search['searchText'] . '%');
                    })
                    ->when($article, function ($query) use ($request) {
                        $query->whereIn('tip_type_id', $request->search['articleType']);
                    })
                    ->when($system, function ($query) use ($request) {
                        $query->whereHas('SystemTypes', function ($query) use ($request) {
                            $query->whereIn('system_types.sys_id', $request->search['systemType']);
                        });
                    })
                    ->with('SystemTypes')
                    ->paginate($request->pagination['perPage'])
            );
        }

        return $tips;
    }

    //  Process an image that is attached to a tech tip
    public function processImage(Request $request)
    {
        $this->authorize('hasAccess', 'create_tech_tip');

        $request->validate([
            'file' => 'mimes:jpeg,bmp,png,jpg,gif'
        ]);

        //  Generate a unique hash as the file name and store it in a publicly accessable folder
        $path = 'img/tip_img';
        $location = Storage::disk('public')->putFile($path, new File($request->file));

        //  Return the full url path to the image
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return response()->json(['location' => Storage::url($location)]);
    }

    //  Create a new Tech Tip form
    public function create()
    {
        $this->authorize('hasAccess', 'create_tech_tip');

        $typesArr   = new TechTipTypesCollection(TechTipTypes::all());
        $systemsArr = new SystemCategoriesCollection(SystemCategories::with('SystemTypes')->get());

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('tips.create', [
            'tipTypes' => $typesArr,
            'sysTypes' => $systemsArr,
        ]);
    }

    //  Submit the form to create a new tech tip
    public function store(Request $request)
    {
        $this->authorize('hasAccess', 'create_tech_tip');

        $request->validate([
            'subject'   => 'required',
            'equipment' => 'required',
            'tipType'   => 'required',
            'tip'       => 'required',
        ]);

        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        //  Verify if there is a file to be processed or not
        if($receiver->isUploaded() === false || $request->_completed)
        {
            Log::debug('about to create a tip');
            $tipID = $this->createTip($request);
            Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
            return response()->json(['tip_id' => $tipID]);
        }

        //  Recieve and process the file
        $save = $receiver->receive();

        //  See if the uploade has finished
        if ($save->isFinished()) {
            $this->saveFile($save->getFile());

            return 'uploaded successfully';
        }

        //  Get the current progress
        $handler = $save->handler();

        Log::debug('Route ' . Route::currentRouteName() . ' visited by User ID-' . Auth::user()->user_id);
        Log::debug('File being uploaded.  Percentage done - ' . $handler->getPercentageDone());
        return response()->json([
            'done'   => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    //  Save a file attached to the link
    private function saveFile(UploadedFile $file)
    {
        $filePath = config('filesystems.paths.tips').DIRECTORY_SEPARATOR.'_tmp';

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
        $fileArr = session('newTipFile') != null ? session('newTipFile') : [];
        $fileArr[] = $fileID;
        session(['newTipFile' => $fileArr]);

        //  Log stored file
        Log::info('File Stored', ['file_id' => $fileID, 'file_path' => $filePath . DIRECTORY_SEPARATOR . $fileName]);
        return $fileID;
    }

    //  Create the tech tip
    private function createTip($tipData)
    {
        //  Remove any forward slash (/) from the Subject Field
        $tipData->merge(['subject' => str_replace('/', '-', $tipData->subject)]);

        //  Enter the tip details and return the tip ID
        $tip = TechTips::create([
            'tip_type_id' => $tipData->tipType,
            'subject'     => $tipData->subject,
            'description' => $tipData->tip,
            'user_id'     => Auth::user()->user_id
        ]);
        $tipID = $tip->tip_id;

        foreach($tipData->equipment as $sys)
        {
            TechTipSystems::create([
                'tip_id' => $tipID,
                'sys_id' => $sys['sys_id']
            ]);
        }

        //  If there were any files uploaded, move them to the proper folder
        if(session('newTipFile') != null)
        {
            $files = session('newTipFile');
            $path = config('filesystems.paths.tips').DIRECTORY_SEPARATOR.$tipID;
            foreach($files as $file)
            {
                $data = Files::find($file);
                //  Move the file to the proper folder
                Storage::move($data->file_link.$data->file_name, $path.DIRECTORY_SEPARATOR.$data->file_name);
                // Update the database
                $data->update([
                    'file_link' => $path.DIRECTORY_SEPARATOR
                ]);

                TechTipFiles::create([
                    'tip_id' => $tipID,
                    'file_id' => $data->file_id
                ]);
            }
        }

        Log::debug('data - ', $tipData->toArray());

        //  Send out the notifications
        if(!$tipData->supressEmail)
        {
            $details = TechTips::find($tipID);
            $users = User::where('active', 1)->whereHas('UserSettings', function($query)
            {
                $query->where('em_tech_tip', 1);
            })->get();

            Notification::send($users, new NewTechTip($details));
        }

        Log::info('New Tech Tip created.  Tip Data - ', $tip->toArray());
        return $tipID;
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



    public function details($id, $subject)
    {
        if(session()->has('newTechTip'))
        {
            session()->forget('newTechTip');
        }



        return response('tip details');
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
