<?php
/*
|   Download page allows users to download files.
|   Note:  file ID and file name must match or file will not be downloaded - this is a security measure to keep random people from accessing any file
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
        $model = $this->model('files');
        $userID = isset($_SESSION['id']) ? 'User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']) : 'Guest '.Security::getRealIpAddr();
        
        $fileData = $model->getFileData($fileID, $fileName);
        
        //  Determine if the file ID and the file name matches
        if(!$fileData || !file_exists($fileData->file_link.$fileData->file_name))
        {
            $msg = $userID.' attempted to download a file that does not exist. File name: '.$fileName.' File ID: '.$fileID;
            Logs::writeLog('Download-Error', $msg);
            $this->template('standard');
            $this->view('files.badFile');
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
                $this->view('files.badFile');
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
}
