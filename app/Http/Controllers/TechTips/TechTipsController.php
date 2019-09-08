<?php

namespace App\Http\Controllers\TechTips;

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

class TechTipsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Tech Tips landing page
    public function index()
    {
        //  Get the types of documents that can be filtered
//        $filterTypes = SystemFileTypes::all();
        $typeArr[] = [
            'text'  => 'Tech Tip',
            'value' => 'tech-tip'
        ];
        $typeArr[] = [
            'text'  => 'Documentation',
            'value' => 'documentation'
        ];
//        foreach($filterTypes as $type)
//        {
//            $typeArr[] = [
//                'text' => $type->description,
//                'value' => str_replace(' ', '-', $type->description)
//            ];
//        }
        
        //  Get the types of systems that can be filtered
        $sysTypes = SystemTypes::orderBy('cat_id', 'ASC')->orderBy('name', 'ASC')->get();
        $sysArr = [];
        foreach($sysTypes as $type)
        {
            $sysArr[] = [
                'text' => $type->name,
                'value' => $type->sys_id
            ];
        }
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('tips.index', [
            'filterTypes' => $typeArr,
            'systemTypes' => $sysArr
        ]);
    }
    
    //  Process an image that is attached to a tech tip
    public function processImage(Request $request)
    {
        $request->validate([
            'image' => 'mimes:jpeg,bmp,png'
        ]);

        $file     = $request->file;
        $fileName = $file->getClientOriginalName();
        $file->storeAs('img/tip_img', $fileName, 'public');

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return json_encode(['location' => '/storage/img/tip_img/'.$fileName]);
    }

    //  Create a new Tech Tip form
    public function create()
    {
        //  Get the types of systems that can be filtered
        $categories = SystemCategories::all();
        $systems    = SystemTypes::orderBy('cat_id', 'ASC')->orderBy('name', 'ASC')->get();
        $sysArr = [];
        $i = 0;
        foreach($categories as $cat)
        {
            $sysArr[$i] = [
                'group' => $cat->name,
            ];
            foreach($systems as $sys)
            {
                if($sys->cat_id === $cat->cat_id)
                {
                    $sysArr[$i]['data'][] = [
                        'name'  => $sys->name,
                        'value' => $sys->sys_id
                    ];
                }
            }
            $i++;
        }
        
        //  Get the types of documents that can be filtered
//        $fileTypes = SystemFileTypes::all();
        $typesArr  = ['Tech Tip', 'Documentation'];
//        foreach($fileTypes as $type)
//        {
//            $typesArr[] = $type->description;
//        }
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('tips.create', [
            'sysTypes' => $sysArr,
            'tipTypes' => $typesArr
        ]);
    }

    //  Submit the form to create a new tech tip
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'systems' => 'required',
            'tipType' => 'required',
            'tip'     => 'required',
        ]);
                
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        
        //  Verify if there is a file to be processed or not (only Tech Tips can be processed without file)
        if($receiver->isUploaded() === false && $request->tipType === 'Tech Tip')
        {
            $tipID = $this->createTip($request);
            Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
            return response()->json(['url' => route('tips.details', [$tipID, urlencode($request->subject)])]);
        }
        else if($receiver->isUploaded() === false)
        {
            Log::error('Upload File Missing - '.$request->toArray());
            throw new UploadMissingFileException();
        }
        
        //  Receive and process the file
        $save = $receiver->receive();
        
        if($save->isFinished())
        {
//            if($request->tipType === 'Tech Tip')
//            {
                if(!$request->session()->has('newTechTip'))
                {
                    $tipID = $this->createTip($request);
                    $request->session()->put('newTechTip', $tipID);
                }
                
                $tipID = session('newTechTip');
            
//                Log::debug('Tip ID - '.$tipID);

                $file     = $save->getFile();
                $path     = config('filesystems.paths.tips').DIRECTORY_SEPARATOR.$tipID;
                $fileName = Files::cleanFilename($path, $file->getClientOriginalName());
                $file->storeAs($path, $fileName);
                
                $newFile = Files::create([
                    'file_name' => $fileName,
                    'file_link' => $path.DIRECTORY_SEPARATOR
                ]);
                
                TechTipFiles::create([
                    'tip_id'  => $tipID,
                    'file_id' => $newFile->file_id
                ]);
                
                Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
                return response()->json(['url' => route('tips.details', [$tipID, urlencode($request->subject)])]);
//            } 
//            else
//            {
//                $file = $save->getFile();
//                
//                $sysArr = is_array($request->systems) ? $request->systems : json_decode($request->systems, true);
//                foreach($sysArr as $sys)
//                {
//                    $sysData = SystemTypes::where('sys_id', $sys['value'])->first();
//                    $catName = SystemCategories::where('cat_id', $sysData->cat_id)->first()->name;
//                    $path = config('filesystems.paths.systems').DIRECTORY_SEPARATOR.strtolower($catName).DIRECTORY_SEPARATOR.$sysData->folder_location;
//                    $fileName = Files::cleanFilename($path, $file->getClientOriginalName());
//                    
//                    Log::debug($fileName);
//                    Log::debug($path);
//                    
//                    $file->storeAs($path, $fileName);
//                    $file = Files::create([
//                        'file_name' => $fileName,
//                        'file_link' => $path.DIRECTORY_SEPARATOR
//                    ]);
//                    
//                    $fileType = SystemFileTypes::where('description', $request->tipType)->first()->type_id;
//                    
//                    SystemFiles::create([
//                        'sys_id'      => $sysData->sys_id,
//                        'type_id'     => $fileType,
//                        'file_id'     => $file->file_id,
//                        'name'        => $request->subject,
//                        'description' => $request->tip,
//                        'user_id'     => Auth::user()->user_id
//                    ]);
//                }
                
//                Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
//                return response()->json(['url' => route('tips.details', [urlencode($sysData->name), urlencode($request->subject)])]);
//            }
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
        
    //  Create the tech tip
    private function createTip($tipData)
    {
        //  Remove any forward slash (/) from the Subject Field
        $tipData->merge(['subject' => str_replace('/', '-', $tipData->subject)]);
        
        //  Enter the tip details and return the tip ID
        $tip = TechTips::create([
            'documentation' => $tipData->tipType === 'documentation' ? true : false,
            'subject'       => $tipData->subject,
            'description'   => $tipData->tip,
            'user_id'       => Auth::user()->user_id
        ]);
        $tipID = $tip->tip_id;
        
        $sysArr = is_array($tipData->systems) ? $tipData->systems : json_decode($tipData->systems, true);
        
        foreach($sysArr as $sys)
        {
            TechTipSystems::create([
                'tip_id' => $tipID,
                'sys_id' => $sys['value']
            ]);
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
    
    public function search(Request $request)
    {
        Log::debug('request Data -> '. $request->getContent());
        $tips = [];
            
        //  Determine if the search form is empty or has data in it
        if($request->searchText === null && empty($request->articleType) && empty($request->sysstemType))
        {
            $tips = TechTips::orderBy('updated_at')->get();
        }
        else
        {

//            $tips = TechTips::
            
            
            
            
            
            
        }
        
            
        
        
        
        
        //  Sort the results for the proper output
        $kbArray = [];
        foreach($tips as $tip)
        {
            $kbArray[] = [
                'url'         => route('tips.details', [urlencode($tip->tip_id), urlencode($tip->subject)]),
                'title'       => $tip->subject,
                'description' => $tip->description,
                'created'     => $tip->created_at,
                'updated'     => date('M d, Y', strtotime($tip->updated_at))
            ];
        }
//        foreach($docs as $doc)
//        {
//            $kbArray[] = [
//                'url'         => route('tips.details', [urlencode($doc->type_id), urlencode($doc->name)]),
//                'title'       => $doc->name,
//                'description' => !$doc->description ? 'No Description Given' : $doc->description,
//                'created'     => $doc->created_at,
//                'updated'     => date('M d, Y', strtotime($doc->updated_at))
//            ];
//        }
        
//        usort($kbArray, function($a, $b)
//        {
//          return $b['created'] <=> $a['created'];
//        });
        
        return response()->json($kbArray);
    }
    
    public function details($id, $subject)
    {
        if(session()->has('newTechTip'))
        {
            session()->forget('newTechTip');
        }
        
        
        
        return response('new tech tip');
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
