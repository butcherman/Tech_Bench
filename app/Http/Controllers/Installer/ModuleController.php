<?php

namespace App\Http\Controllers\Installer;

use Zip;
use App\Files;
use Module;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:is_installer']);
    }

    public function index()
    {
        return view('installer.moduleIndex');
    }

    public function getEnabled()
    {
        $moduleList = [];
        $mods = Module::allEnabled();
        foreach($mods as $name => $mod)
        {
            $moduleList[] = [
                'name' => $name,
                'status' => 'Enabled',
            ];
        }
        $mods = Module::allDisabled();
        foreach ($mods as $name => $mod) {
            $moduleList[] = [
                'name' => $name,
                'status' => 'Disabled',
            ];
        }

        return $moduleList;
    }

    public function getStaged()
    {
        $moduleList = [];
        $mods = Storage::disk('staging')->files('modules');
        foreach($mods as $mod)
        {
            $baseName = explode('/', $mod);
            if($baseName[1] !== '.gitignore')
            {
                $moduleList[] = [
                    'name' => $baseName[1],
                ];
            }
        }

        return $moduleList;
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        //  Verify that the upload is valid and being processed
        if ($receiver->isUploaded() === false) {
            Log::error('Upload File Missing - ' .
                /** @scrutinizer ignore-type */
                $request->toArray());
            throw new UploadMissingFileException();
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
        $filePath = config('filesystems.disks.staging.root') . DIRECTORY_SEPARATOR . 'modules'.DIRECTORY_SEPARATOR;

        //  Clean the file and store it
        $fileName = Files::cleanFilename($filePath, $file->getClientOriginalName());
        Storage::disk('staging')->putFileAs('modules', $file, $fileName);

        return true;
    }

    public function activate($name)
    {
        //
        if(Storage::disk('staging')->missing('modules'.DIRECTORY_SEPARATOR.$name))
        {
            return response()->json(['success' => false]);
        }

        $modName = str_replace('.zip', '', $name);
        $module = Zip::open(config('filesystems.disks.staging.root').DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$name);

        $module->extract(config('modules.paths.modules').DIRECTORY_SEPARATOR.$modName);
        $module->close();

        Artisan::call('module:enable',  ['module' => $modName]);
        Artisan::call('module:migrate', ['module' => $modName]);
        $this->delStaged($name);

        return response()->json(['success' => true]);
    }

    public function enable($name)
    {
        Artisan::call('module:enable', ['module' => $name]);

        return response()->json(['success' => true]);
    }

    public function disable($name)
    {
        Artisan::call('module:disable', ['module' => $name]);

        return response()->json(['success' => true]);
    }

    public function delStaged($name)
    {
        Storage::disk('staging')->delete('modules'.DIRECTORY_SEPARATOR.$name);

        return response()->json(['success' => true]);
    }
}
