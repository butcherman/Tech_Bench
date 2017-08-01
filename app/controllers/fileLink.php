<?php
/*
|   File-Link Controller allows the visitors to upload files for users to access.
|   This is generally helpful when a user needs access to a file that is too large to email
*/

class FileLink extends Controller
{
    //  Landing page 
    public function index($linkHash)
    {
        $model = $this->model('fileLinks');
        
        $linkID = $model->getLinkID($linkHash);
        
        if(!$linkID)
        {
            $this->view('error.badLink');
        }
        else if($model->isLinkExpired($linkID->link_id))
        {
            $this->view('links.expiredLink');
        }
        else
        {
            $linkID = $linkID->link_id;
            $linkFiles = $model->getLinkFiles($linkID);
            
            $data['linkID'] = $linkID;
            $data['files'] = '';
            foreach($linkFiles as $file)
            {
                if(is_numeric($file->added_by))
                {
                    $data['files'] .= '<tr><td><a href="/download/'.$file->file_id.'/'.$file->file_name.'" title="Click to Download">'.$file->file_name.'</a></td><td>'.date('M j, Y', strtotime($file->added_on)).'</td></tr>';
                }
            }
            
            $this->view('links.visitor.validLink', $data);
        }
        
        $this->template('standard');
        $this->render();
    }
    
    public function submitNewFIle($linkID)
    {
        $user = $_POST['name'];
        $model = $this->model('fileLinks');
        $fileModel = $this->model('files');
        
        $path = Config::getFile('uploadRoot').Config::getFile('uploadPath').$linkID.Config::getFile('slash');
        $fileModel->setFileLocation($path);
        $owner = $model->getLinkOwner($linkID);
        
        //  Insert the file
        $fileID = $fileModel->processFiles($_FILES, $user, 'open');
        $model->insertLinkFile($linkID, $fileID[0], $user);
        if(!empty($_POST['notes']))
        {
            $model->insertFileLinkNote($fileID[0], $_POST['notes']);
        }
        
        //  Write a log to note the tech tip
        $msg = 'New File added to link ID: '.$linkID.' by '.$user;
        Logs::writeLog('File-Links', $msg);
        
        //  Create a notification for the dashboard
        $msg = 'New File added to link ID: '.$linkID.' by '.$user;
        $lnk = '/links/details/'.$linkID;
        Template::notifyOneUser($msg, $lnk, $owner);
        
        //  Email all users about the tech tip
//        $tipData = $model->getTipData($tipID);        
//        
//        $data = [
//            'baseURL' => Config::getCore('baseURL'),
//            'title' => $tipData->title,
//            'tipID' => $tipData->tip_id,
//            'date' => $tipData->added_on,
//            'author' => Template::getUserName($tipData->user_id),
//            'tip' => $tipData->details
//        ];
//        $emBody = $this->emailView('tips.newTechTip', $data);
//        Email::init();
//        $emailAddresses = Email::getAddresses('em_tech_tip');
//        Email::addSubject('Tech Bench Notification: New Tech Tip');
//        Email::addUser($emailAddresses);
//        Email::addBody($emBody);
//        Email::send();
        
        $this->render('success');
    }
}
