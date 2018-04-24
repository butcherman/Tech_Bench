<?php
/*
|   File-Link Controller allows the visitors to upload files for users to access.
|   This is generally helpful when a user needs access to a file that is too large to email 
*/

class FileLink extends Controller
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
    
    //  Landing page 
    public function index($linkHash)
    {
        $model = $this->model('fileLinks');
        
        $linkID = $model->getLinkID($linkHash);
        
        if(!$linkID)
        {
            $this->view('error/bad_link');
            
            //  Note in log file
            $msg = 'Visitor '.Security::getRealIpAddr().' attempted to acces a bad link hash - '.$linkHash;
            Logs::writeLog('File-Links', $smg);
        }
        else if($model->isLinkExpired($linkID->link_id))
        {
            $this->view('links/expired_link');
            
            //  Note in log file
            $msg = 'Visitor '.Security::getRealIpAddr().' attempted to acces an expired link hash - '.$linkHash;
            Logs::writeLog('File-Links', $msg);
        }
        else
        {
            $linkID = $linkID->link_id;
            $linkFiles = $model->getLinkFiles($linkID);
            $linkInstructions = $model->getLinkInstructions($linkID);
            
            $data = [
                'allow'        => $model->checkLinkUpload($linkID),
                'linkID'       => $linkID,
                'instructions' => $linkInstructions->instruction,
                'files'        => ''
            ];

            $fileNums = [];
            foreach($linkFiles as $file)
            {
                if(is_numeric($file->added_by))
                {
                    $data['files'] .= '<tr><td><a href="/download/'.$file->file_id.'/'.$file->file_name.'" title="Click to Download">'.$file->file_name.'</a></td><td>'.date('M j, Y', strtotime($file->added_on)).'</td></tr>';
                    $fileNums[] = array($file->file_id, $file->file_name);
                }
            }

            //  If there is more than one file, create download all link
            if(count($fileNums) > 1)
            {
                $data['files'] .= '<tr><td colspan="2" class="text-center"><a href="/download/zipFile/" class="btn btn-default"><span class="glyphicon glyphicon-download-alt"></span> Download All Files</a></td></tr>';
            }
            
            //  Allow a download all array
            $_SESSION['download_all'] = $fileNums;
            
            $this->view('links/visitor_valid_link', $data);
        }
        
        $this->template('standard');
        $this->render();
    }
    
    public function submitNewFile($linkID)
    {
        $user      = $_POST['name'];
        $model     = $this->model('fileLinks');
        $fileModel = $this->model('files');

        $path     = Config::getFile('uploadRoot').Config::getFile('uploadPath').$linkID.Config::getFile('slash');
        $fileModel->setFileLocation($path);
        $owner    = $model->getLinkOwner($linkID);
        $linkData = $model->getLinkDetails($linkID);
        
        //  Insert the files
        $fileIDs = $fileModel->processFiles($fileModel->reArrayFiles($_FILES['file']), $user, 'open');

        foreach($fileIDs as $fileID)
        {
            $model->insertLinkFile($linkID, $fileID, $user);
            if(!empty($_POST['notes']))
            {
                $model->insertFileLinkNote($fileID, $_POST['notes']);
            }
        }
        
        //  Write a log to note the tech tip
        $msg = 'New File added to link ID: '.$linkID.' by '.$user;
        Logs::writeLog('File-Links', $msg);
        
        //  Create a notification for the dashboard
        $msg = 'New File added to link ID: '.$linkID.' by '.$user;
        $lnk = '/links/details/'.$linkID;
        Template::notifyOneUser($msg, $lnk, $owner);
        
        //  Email all users about the tech tip
        $data = [
            'baseURL' => Config::getCore('baseURL'),
            'title'   => $linkData->link_name,
            'addedBy' => $user,
            'linkID'  => $linkID
        ];
        
        Email::init();
        if(Email::getValidAddress('em_file_link', $owner))
        {
            $emailAddresses = Email::getValidAddress('em_file_link', $owner);
            $emBody = $this->emailView('links.newFile', $data);
            
            Email::addSubject('New File Uploaded for File LInk '.$linkData->link_name);
            Email::addUser($emailAddresses);
            Email::addBody($emBody);
            Email::send();
        }
        
        $this->render('success');
    }
}
