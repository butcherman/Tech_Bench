<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Backup landing page
    public function index()
    {        
        return view('installer.backupIndex');
    }
    
    public function loadBackups()
    {
        $backups = Storage::disk('backup')->files();
        
        return view('installer.backupShow', [
            'backups' => $backups
        ]);
    }
    
    //  Manual backup
    public function backupNow()
    {
        Artisan::call('backup:now', [
            'type' => 'manual'
        ]);
        
        return 'success';
    }
    
    //  Download an existing backup
    public function downloadBackup($name)
    {
        return Storage::disk('backup')->download($name);
    }
    
    //  Delete a backup
    public function deleteBackup($name)
    {
        Storage::disk('backup')->delete($name);
        return 'success';
    }
}
