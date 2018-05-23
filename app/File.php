<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $primaryKey = 'file_id';
    protected $fillable = ['file_name', 'file_link', 'mime_type'];
    
    public function systemFiles()
    {
        return $this->hasMany('App\SystemFiles', 'file_id', 'file_id');
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
}
