<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Files extends Model
{
    protected $primaryKey = 'file_id';
    protected $fillable = ['file_name', 'file_link', 'mime_type'];
    
    public function systemFiles()
    {
        return $this->hasMany('App\SystemFiles', 'file_id', 'file_id');
    }
    
    public function fileLinkFiles()
    {
        return $this->hasMany('App\FileLinkFiles', 'file_id', 'file_id');
    }
    
    public function fileLinkNotes()
    {
        return $this->hasOne('App\FileLinkNotes', 'file_id', 'file_id');
    }
    
    public static function cleanFileName($path, $fileName)
    {
        $fileName = str_replace(' ', '_', $fileName);
        
        if(Storage::exists($path.DIRECTORY_SEPARATOR.$fileName))
        {
            
            $fileParts = pathinfo($fileName);
            $extension = isset($fileParts['extension']) ? ('.'.$fileParts['extension']) : '';
            
            //  check for matching file names
            if (preg_match('/(.*?)(\d+)$/', $fileParts['filename'], $match)) 
            {
                // Have a number; increment it
                $base = $match[1];
                $number = intVal($match[2]);
            } 
            else 
            {
                // No number; add one
                $base = $fileParts['filename'];
                $number = 0;
            }
            
            do 
            {
                $fileName = $base.'('.++$number.')'.$extension;
            } while (Storage::exists($path.DIRECTORY_SEPARATOR.$fileName));
        }
        
        return $fileName;
    }
    
    public static function deleteFile($fileID)
    {
        try
        {
            $fileData = Files::find($fileID);
            $fileLink = $fileData->file_link.$fileData->file_name;
            $fileData->delete();
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            return false;
        }
        
        Storage::delete($fileLink);
            
        Log::info('File Deleted', ['file_id' => $fileID, 'file_name' => $fileLink, 'user_id' => Auth::user()->user_id]);
        
        return true;
    }
}
