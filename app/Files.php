<?php

namespace App;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Files extends Model
{
    protected $primaryKey = 'file_id';
    protected $fillable   = ['file_name', 'file_link', 'public'];
    protected $hidden     = ['created_at', 'updated_at', 'file_link'];

    // //  Remove any illegal characters from filename and make sure it is unique
    // public static function cleanFileName($path, $fileName)
    // {
    //     Log::debug('Preparing to clean a file. Current Name - '.$fileName.'. Current Path - '.$path);
    //     //  Remove all spaces
    //     $fileName = str_replace(' ', '_', $fileName);
    //     Log::debug('Cleaned Filename. Current Name - ' . $fileName . '. Current Path - ' . $path);

    //     //  Determine if the filename already exists
    //     if(Storage::exists($path.DIRECTORY_SEPARATOR.$fileName))
    //     {
    //         $fileParts = pathinfo($fileName);
    //         $extension = isset($fileParts['extension']) ? ('.'.$fileParts['extension']) : '';

    //         //  Look to see if a number is already appended to a file.  (example - file(1).pdf)
    //         if(preg_match('/(.*?)(\d+)$/', $fileParts['filename'], $match))
    //         {
    //             // Has a number, increment it
    //             $base = $match[1];
    //             $number = intVal($match[2]);
    //         }
    //         else
    //         {
    //             // No number, add one
    //             $base = $fileParts['filename'];
    //             $number = 0;
    //         }

    //         //  Increase the number until one that is not in use is found
    //         do
    //         {
    //             Log::debug('Filename ' . $fileName . ' already exists.  Appending name.');
    //             $fileName = $base.'('.++$number.')'.$extension;
    //         } while(Storage::exists($path.DIRECTORY_SEPARATOR.$fileName));
    //     }

    //     return $fileName;
    // }

    // //  Delete a file from both the database and file system
    // public static function deleteFile($fileID)
    // {
    //     Log::debug('Attempting to delete file ID '.$fileID);
    //     $fileLink = '';
    //     try
    //     {
    //         //  Try to delete file from database - will throw error if foreign key is in use
    //         $fileData = Files::find($fileID);
    //         $fileLink = $fileData->file_link.$fileData->file_name;
    //         $fileData->delete();
    //     }
    //     catch(\Illuminate\Database\QueryException $e)
    //     {
    //         //  Unable to remove file from the database
    //         Log::warning('Attempt to delete file failed.  Reason - '.$e.'. Additional Data - ', ['file_id' => $fileID, 'file_name' => $fileLink, 'user_id' => Auth::user()->user_id]);
    //         return false;
    //     }

    //     //  Delete the file from the storage system
    //     Storage::delete($fileLink);

    //     Log::notice('File deleted by '.Auth::user()->full_name.'. Additional Information - ', ['file_id' => $fileID, 'file_name' => $fileLink, 'user_id' => Auth::user()->user_id]);
    //     return true;
    // }
}
