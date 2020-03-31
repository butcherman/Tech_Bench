<?php

namespace App\Http\Controllers\TechTips;

use App\User;
use App\Files;
use App\TechTips;
use App\TechTipFavs;
use App\TechTipFiles;
use App\TechTipTypes;
use App\TechTipSystems;
use Illuminate\Http\File;
use App\SystemCategories;
use Illuminate\Http\Request;
use App\Notifications\NewTechTip;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\TechTipsCollection;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\TechTipTypesCollection;
use App\Http\Resources\SystemCategoriesCollection;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use App\Http\Resources\SystemCategoriesCollection as CategoriesCollection;

class TechTipsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Tech Tips landing page
    public function index()
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);

        $tipTypes = new TechTipTypesCollection(TechTipTypes::all());
        $sysList  = new CategoriesCollection(SystemCategories::with('SystemTypes')->with('SystemTypes.SystemDataFields.SystemDataFieldTypes')->get());
        return view('tips.index', [
            'tipTypes' => $tipTypes,
            'sysTypes' => $sysList,
        ]);
    }

    //  Search for an existing tip - If no paramaters, return all tips
    public function search(Request $request)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

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
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        $this->authorize('hasAccess', 'Create Tech Tip');

        $request->validate([
            'file' => 'mimes:jpeg,bmp,png,jpg,gif'
        ]);

        //  Generate a unique hash as the file name and store it in a publicly accessable folder
        $path = 'img/tip_img';
        $location = Storage::disk('public')->putFile($path, new File($request->file));

        //  Return the full url path to the image
        return response()->json(['location' => Storage::url($location)]);
    }

    //  Create a new Tech Tip form
    public function create()
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);
        $this->authorize('hasAccess', 'Create Tech Tip');

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
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        $this->authorize('hasAccess', 'Create Tech Tip');

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

        Log::debug('File being uploaded.  Percentage done - ' . $handler->getPercentageDone());
        return response()->json([
            'done'   => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    //  Save a file attached to the link
    private function saveFile(UploadedFile $file, $id = '_tmp')
    {
        $filePath = config('filesystems.paths.tips').DIRECTORY_SEPARATOR.$id;

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
        if($id === '_tmp')
        {
            $fileArr = session('newTipFile') != null ? session('newTipFile') : [];
            $fileArr[] = $fileID;
            session(['newTipFile' => $fileArr]);
        }
        else
        {
            TechTipFiles::create([
                'tip_id' => $id,
                'file_id' => $fileID,
            ]);
        }

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

        //  Send out the notifications
        if(!$tipData->supressEmail)
        {
            $details = TechTips::find($tipID);
            $users = User::whereHas('UserSettings', function($query)
            {
                $query->where('em_tech_tip', 1);
            })->get();

            Notification::send($users, new NewTechTip($details));
        }

        Log::info('New Tech Tip created by '.Auth::user()->full_name.'.  Tip Data - ', $tip->toArray());
        return $tipID;
    }

    //  Details controller - will move to the show controller with just the tech tip id
    public function details($id, $subject)
    {
        if (session()->has('newTipFile')) {
            session()->forget('newTipFile');
        }

        return $this->show($id);
    }

    //  Show the details about the tech tip
    public function show($id)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);

        $tipData = TechTips::where('tip_id', $id)->with('User')->with('SystemTypes')->first();

        if(!$tipData)
        {
            return view('tips.tipNotFound');
        }

        $isFav = TechTipFavs::where('user_id', Auth::user()->user_id)->where('tip_id', $id)->first();
        $files = TechTipFiles::where('tip_id', $id)->with('Files')->get();

        return view('tips.details', [
            'details' => $tipData,
            'isFav'   => empty($isFav) ? 'false' : 'true',
            'files'   => $files,
        ]);
    }

    //  Add or remove this tip as a favorite of the user
    public function toggleFav($action, $id)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);

        switch ($action) {
            case 'add':
                TechTipFavs::create([
                    'user_id' => Auth::user()->user_id,
                    'tip_id' => $id
                ]);
                break;
            case 'remove':
                $tipFav = TechTipFavs::where('user_id', Auth::user()->user_id)->where('tip_id', $id)->first();
                $tipFav->delete();
                break;
        }

        Log::debug('Tech Tip Bookmark Updated.', [
            'user_id' => Auth::user()->user_id,
            'tip_id' => $id,
            'action'  => $action
        ]);
        return response()->json(['success' => true]);
    }

    //  Edit an existing tech tip
    public function edit($id)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);

        $this->authorize('hasAccess', 'Edit Tech Tip');
        $tipData = TechTips::where('tip_id', $id)->with('User')->with('SystemTypes')->with('TechTipTypes')->first();

        if (!$tipData) {
            return view('tips.tipNotFound');
        }

        $typesArr   = new TechTipTypesCollection(TechTipTypes::all());
        $systemsArr = new SystemCategoriesCollection(SystemCategories::with('SystemTypes')->get());
        $files = TechTipFiles::where('tip_id', $id)->with('Files')->get();

        return view('tips.editTip', [
            'tipTypes' => $typesArr,
            'sysTypes' => $systemsArr,
            'details' => $tipData,
            'files'   => $files,
        ]);
    }

    //  Store the edited Tech Tip
    public function update(Request $request, $id)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        $this->authorize('hasAccess', 'Edit Tech Tip');

        $request->validate([
            'subject'   => 'required',
            'equipment' => 'required',
            'tipType'   => 'required',
            'tip'       => 'required',
        ]);

        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        //  Verify if there is a file to be processed or not
        if ($receiver->isUploaded() === false || $request->_completed) {
            $this->storeUpdatedTip($request, $id);
            Log::debug('Route ' . Route::currentRouteName() . ' visited by User ID-' . Auth::user()->user_id);
            return response()->json(['tip_id' => $id]);
        }

        //  Recieve and process the file
        $save = $receiver->receive();

        //  See if the uploade has finished
        if ($save->isFinished()) {
            $this->saveFile($save->getFile(), $id);

            return 'uploaded successfully';
        }

        //  Get the current progress
        $handler = $save->handler();

        Log::debug('File being uploaded.  Percentage done - ' . $handler->getPercentageDone());
        return response()->json([
            'done'   => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    //  Store the updated tip
    public function storeUpdatedTip($tipData, $id)
    {
        $this->authorize('hasAccess', 'Edit Tech Tip');

        //  Remove any forward slash (/) from the Subject Field
        $tipData->merge(['subject' => str_replace('/', '-', $tipData->subject)]);

        //  Enter the tip details and return the tip ID
        TechTips::find($id)->update([
            'tip_type_id' => $tipData->tipType,
            'subject'     => $tipData->subject,
            'description' => $tipData->tip,
            // 'user_id'     => Auth::user()->user_id TODO - updated user who modified tip
        ]);

        //  Add any additional equipment types to the tip
        $tipEquip = TechTipSystems::where('tip_id', $id)->get();
        foreach($tipData->equipment as $equip)
        {
            $found = false;
            foreach($tipEquip as $key => $value)
            {
                if($equip['sys_id'] == $value->sys_id)
                {
                    $tipEquip->forget($key);
                    $found = true;
                    break;
                }
            }
            if(!$found)
            {
                TechTipSystems::create([
                    'tip_id' => $id,
                    'sys_id' => $equip['sys_id'],
                ]);
            }
        }

        //  Remove the remainaing equipment types that have been removed
        foreach($tipEquip as $remaining)
        {
            TechTipSystems::find($remaining->tip_tag_id)->delete();
        }

        //  Remove any files that no longer apply
        foreach($tipData->deletedFileList as $file)
        {
            $file = TechTipFiles::find($file);
            $fileID = $file->file_id;
            $file->delete();
            //  Try to delete the file itself
            Files::deleteFile($fileID);
        }

        return true;
    }

    //  Soft delet the Tech Tip
    public function destroy($id)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);

        $this->authorize('hasAccess', 'Delete Tech Tip');

        //  Remove the Tip from any users favorites
        TechTipFavs::where('tip_id', $id)->delete();

        //  Disable the tip
        TechTips::find($id)->delete();
        Log::warning('User - '.Auth::user()->user_id.' deleted Tech Tip ID - '.$id);
        return response()->json(['success' => true]);
    }
}
