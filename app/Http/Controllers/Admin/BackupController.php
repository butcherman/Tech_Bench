<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BackupRequest;
use App\Jobs\ApplicationBackupJob;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Traits\AppSettingsTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    use AppSettingsTrait;

    /**
     * Application Backup Landing Page
     */
    public function index()
    {
        $this->authorize('viewAny', AppSettings::class);

        $backupList = Storage::disk('backups')->files();
        $backups    = [];
        foreach($backupList as $b)
        {
            $parts = pathinfo($b);
            if($parts['extension'] === 'zip')
            {
                $backups[] = [
                    'name' => $b,
                    'date' => Carbon::createFromTimestamp(Storage::disk('backups')->lastModified($b))->format('M d, Y h:m a'),
                ];
            }
        }

        return Inertia::render('Admin/Backups', [
            'settings' => [
                'enabled' => (bool) config('app.backups.enabled'),
                'number'  => config('app.backups.number'),
            ],
            'backups' => $backups,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Update the automatic backup settings
     */
    public function store(BackupRequest $request)
    {
        $this->saveSettings('app.backups.enabled', $request->enabled);
        $this->saveSettings('app.backups.number',  $request->number);

        return back()->with([
            'message' => 'Automatic Backup Settings Updated',
            'type'    => 'success',
        ]);
    }

    /**
     * Run a system backup
     */
    public function show($id)
    {
        if($id === 'run')
        {
            ApplicationBackupJob::dispatch();
            return back()->with([
                'message' => 'Backup Started Successfully - currently running in background',
                'type'    => 'success',
            ]);
        }

        return abort(404);
    }

    /**
     * Download a backup file
     */
    public function edit($id)
    {
        //  Verify that the backup file exists
        if(!Storage::disk('backups')->exists($id))
        {
            abort(404, 'The backup file you are looking for does not exist');
        }

        return Storage::disk('backups')->download($id);
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
     * Delete a backup file
     */
    public function destroy($id)
    {
        //  Verify that the backup file exists
        if(!Storage::disk('backups')->exists($id))
        {
            abort(404, 'The backup file you are looking for does not exist');
        }

        Storage::disk('backups')->delete($id);
        return back()->with([
            'message' => $id.' deleted',
            'type'    => 'warning',
        ]);
    }
}
