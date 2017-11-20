<?php
/*
|   Download page allows users to download files.
|   Note:  file ID and file name must match or file will not be downloaded - this is a security 
|   measure to keep random people from accessing any file
|   Files can also be limited to logged in users and security levels
*/

class Download extends Controller
{
    public function __construct()
    {
        Security::setPageLevel('open');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
    }
    
    //  Function to download a file from the database
    public function index($fileID = '', $fileName = '')
    {
        $model  = $this->model('files');
        $userID = isset($_SESSION['id']) ? 'User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']) : 'Guest '.Security::getRealIpAddr();
        
        $fileData = $model->getFileData($fileID, $fileName);
        
        //  Determine if the file ID and the file name matches
        if(!$fileData || !file_exists($fileData->file_link.$fileData->file_name))
        {
            $msg = $userID.' attempted to download a file that does not exist. File name: '.$fileName.' File ID: '.$fileID;
            Logs::writeLog('Download-Error', $msg);
            $this->template('standard');
            $this->view('error/bad_file');
            $this->render();
        }
        else
        {
            //  Determine if the user has permission to download the file
            Security::setPageLevel($fileData->permission_description);
            if(!Security::doIBelong())
            {
                $msg = $userID.' attempted to download a file without the proper permissions. File name: '.$fileName.' File ID: '.$fileID;
                Logs::writeLog('Download-Error', $msg);
                $this->template('standard');
                $this->view('error/bad_file');
                $this->render();
            }
            else
            {
                $msg = 'User: '.$userID.' downloaded file ID: '.$fileID;
                Logs::writeLog('Download_Activity', $msg);

                //  Prepare header information for file download
                header('Content-Description:  File Transfer');
                header('Content-Type:  '.$fileData->mime_type);
                header('Content-Disposition: attachment; filename='.basename($fileData->file_name));
                header('Content-Transfer-Encoding:  binary');
                header('Expires:  0');
                header('Cache-Control:  must-revalidate, post-check=0, pre-check=0');
                header('Pragma:  public');
                header('Content-Length:  '.filesize($fileData->file_link.$fileData->file_name));

                //  Begin the file download.  File is broken into sections to better be handled by browser
                set_time_limit(0);
                $file = @fopen($fileData->file_link.$fileData->file_name,"rb");
                while(!feof($file))
                {
                    print(@fread($file, 1024*8));
                    ob_flush();
                    flush();
                }
            }
        }
    }
    
    //  Download multiple files at once as a zip file
    public function zipFile()
    {
        //  Get the array of files to be downloaded - come in the format of xxx-xxx-xxx where - is the separater
        $fileArr = $_SESSION['download_all'];
        
        $model  = $this->model('files');
        $userID = isset($_SESSION['id']) ? 'User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']) : 'Guest '.Security::getRealIpAddr();
                
        $zipName = Config::getFile('uploadRoot').Config::getFile('default').'files.zip';
        $zip     = new ZipArchive;
        $zip->open($zipName, ZipArchive::CREATE);
        foreach($fileArr as $file)
        {

            $fileData = $model->getFileData($file[0], $file[1]);
            $filePath = $fileData->file_link.$fileData->file_name;
            
            $zip->addFile($filePath, $file[1]);
        }
        $zip->close();

        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="'.basename($zipName).'"');
        header("Content-length: " . filesize($zipName));
        header("Pragma: no-cache");
        header("Expires: 0");
        
        //  Begin the file download.  File is broken into sections to better be handled by browser
        set_time_limit(0);
        $file = @fopen($zipName,"rb");
        while(!feof($file))
        {
            print(@fread($file, 1024*8));
            ob_flush();
            flush();
        }
        
        unlink($zipName);
    }
    
    //  Download All Log Files
    public function allLogFiles()
    {
        Security::setPageLevel('site admin');
        if(!Security::doIBelong())
        {
            header('Location: /err/restricted');
            die();
        }
        
        $logFiles = scanDir(__DIR__.'/../../logs/');
        
        $zipName = Config::getFile('uploadRoot').Config::getFile('default').'log_files.zip';
        $zip     = new ZipArchive;
        $zip->open($zipName, ZipArchive::CREATE);
        foreach($logFiles as $file)
        {
            $parts = pathinfo($file);
            if($parts['extension'] === 'log')
            {
                $logFile = __DIR__.'/../../logs/'.$file;
                $zip->addFile($logFile, $file);
            }
        }
        $zip->close();
        
        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="'.basename($zipName).'"');
        header("Content-length: " . filesize($zipName));
        header("Pragma: no-cache");
        header("Expires: 0");
        
        //  Begin the file download.  File is broken into sections to better be handled by browser
        set_time_limit(0);
        $file = @fopen($zipName,"rb");
        while(!feof($file))
        {
            print(@fread($file, 1024*8));
            ob_flush();
            flush();
        }
        
        unlink($zipName);
    }
    
    //  Download a backup file
    public function backup($fileName)
    {
        $path = __DIR__.'/../../backups/'.$fileName;
        
        if(!file_exists($path))
        {
            $msg = $userID.' attempted to download a file that does not exist. File name: '.$fileName.' File ID: '.$fileID;
            Logs::writeLog('Download-Error', $msg);
            $this->template('standard');
            $this->view('error/bad_file');
            $this->render();
        }
        else
        {
            Security::setPageLevel('site admin');
            if(!Security::doIBelong())
            {
                $_SESSION['returnURL'] = $_GET['url'];
                header('Location: /err/restricted');
                die();
            }

            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            header("Content-length: " . filesize($path));
            header("Pragma: no-cache");
            header("Expires: 0");

            //  Begin the file download.  File is broken into sections to better be handled by browser
            set_time_limit(0);
            $file = @fopen($path,"rb");
            while(!feof($file))
            {
                print(@fread($file, 1024*8));
                ob_flush();
                flush();
            }
        }
    }
}
