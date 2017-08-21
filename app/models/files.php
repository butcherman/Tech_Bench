<?php
/*
|   File model will process all Tech Bench files.  It will upload, move, and delete files
*/
class Files
{
    private $fileLocation;
    
    //  Function to set the location the file will be stored
    public function setFileLocation($fileLocation)
    {
        $this->fileLocation = $fileLocation;
    }
    
    //  Function to get all data associated with a file
    public function getFileData($fileID, $fileName)
    {
        $qry = 'SELECT `file_name`, `file_link`, `mime_type`, `permissions`.`permission_description` FROM `files` JOIN `permissions` ON `files`.`permission_id` = `permissions`.`permission_id` WHERE `file_id` = :id AND `file_name` = :name';
        $result = Database::getDB()->prepare($qry);
        $result->execute(['id' => $fileID, 'name' => $fileName]);
        
        return $result->fetch();
    } 
    
    //  Function to process an uploaded file - also allows for multiple file uploads
    public function processFiles($fileData, $userID = '', $permission = 'admin')
    {
        $success = [];

        //  Determine if the file location has been set or not
        if(empty($this->fileLocation))
        {
            $this->setFileLocation(Config::getFile('uploadRoot').Config::getFile('default'));
        }
        
        //  Process the files by moving them to the proper folder, and placing in the database
        foreach($fileData as $file)
        {
            //  Determine if there is an error in the file
            if($file['error'] > 0)
            {
                $msg = 'FILE '.$file['name'].' failed to upload by user '.$userID.'. Error '.$file['error'];
                Logs::writeLog('File-Error', $msg);
                $success = false;
                break;
            }
            //  Determine if the file is too big
            else if($file['size'] > Config::getFile('maxUpload'))
            {
                $msg = 'File '.$file['name'].' failed to upload by user '.$userID.'. Error: File is too big '.$file['size'];
                Logs::writeLog('File-Error', $msg);
                $success = false;
                break;
            }
            //  No errors, process file
            else
            {
                //  Remove all illegal characters from the file
                $fileName = $this->cleanName($file['name']);
                //  Make sure there are no other files with the same name
                $fileName = $this->findDups($fileName);
                
                //  Make sure that the destination folder is writable
                if(is_dir($this->fileLocation) && is_writable($this->fileLocation))
                {
                    //  Move the file to the proper location
                    if(move_uploaded_file($file['tmp_name'], $this->fileLocation.$fileName))
                    {
                        $qry = 'INSERT INTO `files` (`file_name`, `file_link`, `mime_type`, `permission_id`) VALUES (:fileName, :link, :mime, (SELECT `permission_id` FROM `permissions` WHERE `permission_description` = :perm))';
                        $qryData = [
                            'fileName' => $fileName,
                            'link' => $this->fileLocation,
                            'mime' => $file['type'],
                            'perm' => $permission
                        ];
                        Database::getDB()->prepare($qry)->execute($qryData);
                      
                        
                        $success[] = Database::getDB()->lastInsertId();
                    }
                    else
                    {
                        $success = false;
                        $msg = 'Unable to Upload File '.$fileName.'. Unable to move file to folder '.$this->fileLocation.'.';
                        Logs::writeLog('File-Error', $msg);
                        break;
                    }
                }
                else
                {
                    $success = false;
                    $msg = 'Unable to Upload File '.$fileName.'. Folder '.$this->fileLocation.' does not exist or cannot be written to.';
                    Logs::writeLog('File-Error', $msg);
                    break;
                }
            }
        }
        
        return $success;
    }
    
    //  Function to create a new folder
    public function createFolder($absolutePath)
    {
        if(!file_exists($absolutePath))
        {
            mkdir($absolutePath, 0777, true);
            return true;
        }
        else
        {
            return false;
        }
    }
    
    //  Function to delete a folder
    public function deleteFolder($absolutePath)
    {
        if(file_exists($absolutePath))
        {
            rmdir($absolutePath);
            return true;
        }
        else
        {
            return false;
        }
    }
    
    //  Function to delete a file from the dabase and file structure
    public function deleteFile($fileID)
    {
        $qry = 'SELECT `file_name`, `file_link` FROM `files` WHERE `file_id` = :fileID';
        $result = Database::getDB()->prepare($qry);
        $result->execute(['fileID' => $fileID]);
        $res = $result->fetch();
        $valid = false;
        
        $fileName = $res->file_link.$res->file_name;
        if(file_exists($fileName))
        {
            $this->eraseFile($fileName);
            $msg = 'File ID: '.$fileID.' deleted by User ID: '.$_SESSION['id'];
            Logs::writeLog('Files', $msg);
            $valid = true;
        }
        else
        {
            $msg = 'Attempted to delete file that did not exist - File ID: '.$fileID;
            Logs::writeLog('File-Error', $msg);
        }
        
        return $valid;
    }
    
    //  Function to clean the name of the file and remove all illegal characters and spaces
    private function cleanName($name)
    {
        return preg_replace("/[^a-z0-9-_\.]/", "_", strtolower($name));
    }

    //  Function to determine if the filename already exists
    private function findDups($fileName)
    {
        if(empty($this->fileLocation))
        {
            $this->fileLocation = Conf::getFile('uploadRoot').Conf::getFile('default');
        }

        //  If the file already exists within the selected folder
        if(file_exists($this->fileLocation.$fileName))
        {
            //  Determine if the file has a valid extension such as .exe or .pdf etc.
            if($pos = strrpos($fileName, '.'))
            {
                $name = substr($fileName, 0, $pos);
                $ext = substr($fileName, $pos);
            }
            else
            {
                $name = $fileName;
            }

            //  Begin the process of re-naming the file 
            $newPath = $this->fileLocation.$fileName;
            $newName = $fileName;
            $counter = 2;
            while(file_exists($newPath))
            {
                $newName = $name.'('.$counter.')'.$ext;
                $newPath = $this->fileLocation.$newName;
                $counter++;
            }

            return $newName;
        }
        else
        {
            return $fileName;
        }
    }
    
     //  Re-arrange file array to a properly sorted array that an be processed
    public function reArrayFiles($file_post)
    {   
        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) 
        {
            foreach ($file_keys as $key) 
            {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }
    
    //  Function to delete a file from the folder structure
    private function eraseFile($filePath)
    {
        unlink($filePath);
    }
}