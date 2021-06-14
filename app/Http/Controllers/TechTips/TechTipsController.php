<?php

namespace App\Http\Controllers\TechTips;

use Inertia\Inertia;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\NewTipRequest;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\FileUploads;
use App\Models\TechTip;
use App\Models\TechTipFile;
use App\Models\TechTipType;
use App\Traits\FileTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TechTipsController extends Controller
{
    use FileTrait;

    protected $disk;
    protected $tmpFolder;

    public function __construct()
    {
        $this->disk      = 'tips';
        $this->tmpFolder = '_tmp';
    }

    /**
     *  Show all of the Tech Tips
     */
    public function index()
    {
        return Inertia::render('TechTips/index');
    }

    /**
     *  Form to create a new Tech Tip
     */
    public function create()
    {
        $this->authorize('create', TechTip::class);

        return Inertia::render('TechTips/create', [
            'tipTypes'  => TechTipType::all(),
            'equipment' => EquipmentCategory::with('EquipmentType')->get(),
        ]);
    }

    /**
     *  Create the new Tech Tip
     */
    public function store(NewTipRequest $request)
    {
        //  Determine if we are in the process of uploading a file, or if this is the initial request
        if($request->file)
        {
            $status = $this->getChunk($request, $this->disk, $this->tmpFolder);

            //  If the file is completely uploaded, save the name and location in session data and move onto the next file
            if($status['done'] === 100)
            {
                $newFile = FileUploads::create([
                    'disk'      => $this->disk,
                    'folder'    => $this->tmpFolder,
                    'file_name' => $status['filename'],
                    'public'    => 1,
                ]);

                $fileArr = session('new-file-upload') !== null ? session('new-file-upload') : [];
                $fileArr[] = $newFile;

                session(['new-file-upload' => $fileArr]);

                return response()->noContent();
            }

            //  Continue the file upload
            return response($status);
        }

        //  All file uploads are done, create the new Tech Tip
        $newTip = TechTip::create([
            'user_id'     => $request->user()->user_id,
            'tip_type_id' => $request->tip_type_id,
            'sticky'      => $request->sticky,
            'subject'     => $request->subject,
            'slug'        => Str::slug($request->subject),
            'details'     => $request->details,
        ]);


        //  Move the files into a folder with the name of the tip_id
        if(session('new-file-upload') !== null)
        {
            $files = session('new-file-upload');
            foreach($files as $file)
            {
                $this->moveFile($file->file_id, $this->disk, $newTip->tip_id);
                TechTipFile::create([
                    'tip_id'  => $newTip->tip_id,
                    'file_id' => $file->file_id,
                ]);
            }

            session()->forget('new-file-upload');
        }

        Log::channel('user')->info('New Tech Tip - '.$request->subject.' was created by '.$request->user()->username);
        return redirect(route('tech-tips.show', $newTip->slug));
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
        return 'show new tip';
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
