<?php
/*
|   Links controller allows users to create links that allow customers to upload or download files
*/
class Links extends Controller
{
    //  Constructor sets the page security level and reroutes if user is not logged in
    public function __construct()
    {
        Security::setPageLevel('tech');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
    }
    
    //  Index function shows all current links and allows user to create new ones
    public function index()
    {
        $model = $this->model('fileLinks');
        $today = date('Y-m-d');
        $userLinks = $model->getLinks($_SESSION['id']);
        
        $data['linkList'] = '';
        foreach($userLinks as $link)
        {
            if($link->expire < $today && $link->expire < date('Y-m-d', strtotime('-30 days')))
            {
                $class = ' class="danger"';
            }
            else if($link->expire < $today)
            {
                $class = ' class="warning"';
            }
            else
            {
                $class = '';
            }
            
            $numFiles = $model->countLinkFiles($link->link_id);

            $data['linkList'] .= '<tr'.$class.'><td><a href="/links/details/'.$link->link_id.'/'.str_replace(' ', '-', $link->link_name).'">'.$link->link_name.'</td><td class="hidden-xs">'.date('M j, Y', strtotime($link->expire)).'</td><td>'.$numFiles.'</td><td class="hidden-xs"><a href="/links/details/'.$link->link_id.'/'.str_replace(' ', '-', $link->link_name).'" title="Edit Link" data-tooltip="tooltip"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;<a href="#edit-modal"  class="delete-link" title="Delete Link" data-tooltip="tooltip" data-toggle="modal" data-link="'.$link->link_id.'"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';
        }
        
        $this->template('techUser');
        $this->view('links/index', $data);
        $this->render();
    }
    
    //  Create function allows users to create a new file link for users
    public function create()
    {
        $this->template('techUser');
        $this->view('links/new_link_form');
        $this->render();
    }
    
    //  Ajax call to submit the new link form
    public function createSubmit()
    {
        $model = $this->model('fileLinks');
        
        $linkID = $model->createLink($_POST['linkName'], $_POST['expire'], $_SESSION['id']);
        
        if(isset($_POST['allowUpload']) && $_POST['allowUpload'])
        {
            $model->allowLinkUpload($linkID);
        }
        
        $fileModel = $this->model('files');
        $path = Config::getFile('uploadRoot').Config::getFile('uploadPath').$linkID;
        $fileModel->createFolder($path);
        
        if(!empty($_FILES))
        {
            $fileModel->setFileLocation($path.Config::getFile('slash'));
            $fileID = $fileModel->processFiles($fileModel->reArrayFiles($_FILES['file']), $_SESSION['id'], 'open');
            foreach($fileID as $id)
            {
                $model->insertLinkFile($linkID, $id, $_SESSION['id']);
            }
        }
        
        //  Note the change in the log files
        $msg = 'New File Link ID: '.$linkID.' created by User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('File-Link', $msg);
        
        $this->render($linkID);
    }
    
    //  Ajax call to delete a link
    public function deleteLink($linkID)
    {
        $model = $this->model('fileLinks');
        //  Determine if there are any files attached to the link
        $files = $model->getLinkFiles($linkID);

        $fileModel = $this->model('files');
        if(!empty($files))
        {
            foreach($files as $file)
            {
                $fileModel->deleteFile($file->file_id);
            }
        }
        $path = Config::getFile('uploadRoot').Config::getFile('uploadPath').$linkID;
        $fileModel->deleteFolder($path);
        
        //  Delete the link
        $model->deleteLink($linkID);
        
        //  Note the change in the log files
        $msg = 'File Link ID: '.$linkID.' deleted by User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('File-Link', $msg);
        
        $this->render('success');
    }
    
    //  Details page shows the details for a specific link
    public function details($linkID)
    {
        $model = $this->model('fileLinks');
        $details = $model->getLinkDetails($linkID);
        
        if(!$details)
        {
            $this->view('error/bad_link');
        }
        else
        {
            $data = [
                'linkName' => $details->link_name,
                'linkID' => $linkID,
                'expire' => $details->expire,
                'link' => Config::getCore('baseURL').'file-link/'.$details->link_hash,
                'allow' => $details->allow_user_upload ? 'Yes' : 'No'
            ];

            $this->view('links/link_details', $data);
        }
        
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to load the user added files
    public function userUploadedFiles($linkID)
    {
        $model = $this->model('fileLinks');
        $files = $model->getLinkFiles($linkID);
        
        $data = '';
        foreach($files as $file)
        {
            if(is_numeric($file->added_by))
            {
                if(!empty($file->upload_note_id))
                {
                    $note = '<a href="#edit-modal" title="Click to view Note" data-toggle="modal" data-tooltip="tooltip" data-noteid="'.$file->upload_note_id.'"><span class="glyphicon glyphicon-open-file"></span></a>';
                }
                else
                {
                    $note = '';
                }
                $data .= '<tr><td><a href="/download/'.$file->file_id.'/'.$file->file_name.'">'.$file->file_name.'</a></td><td class="hidden-xs">'.date('M j, Y', strtotime($file->added_on)).'</td><td>'.$note.'</td><td class="hidden-xs"><a href="#edit-modal" title="Delete File" class="delete-file-link pointer" data-fileID="'.$file->file_id.'" data-tooltip="tooltip" data-toggle="modal"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';
            }
        }
            
        if(empty($data))
        {
            $data = '<tr><td colspan="4" class="text-center">No Files</td></tr>';
        }
        
        $this->render($data);
    }
    
    //  Ajax call to load the customer added files
    public function customerUploadedFiles($linkID)
    {
        $model = $this->model('fileLinks');
        $files = $model->getLinkFiles($linkID);

        $data = '';
        foreach($files as $file)
        {
            if(!is_numeric($file->added_by))
            {
                if(!empty($file->upload_note_id))
                {
                    $note = '<a href="#edit-modal" class="view-note-link" title="Click to view Note" data-toggle="modal" data-tooltip="tooltip" data-noteid="'.$file->upload_note_id.'"><span class="glyphicon glyphicon-open-file"></span></a>';
                }
                else
                {
                    $note = '';
                }
                
                $data .= '<tr><td><a href="/download/'.$file->file_id.'/'.$file->file_name.'">'.$file->file_name.'</a></td><td class="hidden-xs">'.date('M j, Y', strtotime($file->added_on)).'</td><td>'.$note.'</td><td class="hidden-xs"><a href="#edit-modal" title="Delete File" class="delete-file-link pointer" data-fileID="'.$file->file_id.'" data-tooltip="tooltip" data-toggle="modal"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';
            }
        }
        
        if(empty($data))
        {
            $data = '<tr><td colspan="4" class="text-center">No Files</td></tr>';
        }
        
        $this->render($data);
    }
    
    //  Load the new file form
    public function newFileForm()
    {
        $this->view('links/new_file_form');
        $this->render();
    }
    
    //  Submit the new file form
    public function newFileSubmit($linkID)
    {
        $model = $this->model('fileLinks');
        $fileModel = $this->model('files');
        $path = Config::getFile('uploadRoot').Config::getFile('uploadPath').$linkID;
        
        $fileModel->setFileLocation($path.Config::getFile('slash'));
        $fileID = $fileModel->processFiles($fileModel->reArrayFiles($_FILES['file']), $_SESSION['id'], 'open');
        foreach($fileID as $id)
        {
            $model->insertLinkFile($linkID, $id, $_SESSION['id']);
        }
        
        //  Note the change in the log files
        $msg = 'File added for Link ID: '.$linkID.' by User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('File-Link', $msg);
        
        $this->render('success');
    }
    
    //  Delete one of the files attached to the link
    public function deleteFile($fileID)
    {
        $model = $this->model('fileLinks');
        $fileModel = $this->model('files');
        
        $fileModel->deleteFile($fileID);
        $model->deleteLinkFile($fileID);
        
        //  Note the change in the log files
        $msg = 'File ID: '.$fileID.' deleted by User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('File-Link', $msg);
        
        $this->render('success');
    }
    
    //  Load a note attached to a file
    public function loadNote($noteID)
    {
        $model = $this->model('fileLinks');
        $note = $model->getFileLinkNote($noteID);
        
        $this->render($note->note);
    }
    
    //  Load the edit link form
    public function editLinkForm($linkID)
    {
        $model = $this->model('fileLinks');
        $link = $model->getLinkDetails($linkID);
        
        $data = [
            'name' => $link->link_name,
            'expire' => $link->expire,
            'allow' => $link->allow_user_upload ? 'checked' : ''
        ];
        
        $this->view('links/edit_link_form', $data);
        $this->render();
    }
    
    //  Submit the edit link form
    public function editLinkSubmit($linkID)
    {
        $model = $this->model('fileLinks');
        $linkData = [
            'name' => $_POST['linkName'],
            'expire' => $_POST['expire'],
            'allow' => isset($_POST['allowUpload']) && $_POST['allowUpload'] ? 1 : 0
        ];
        
        $model->updateLink($linkID, $linkData);
        
        //  Note the change in the log files
        $msg = 'File Link ID: '.$linkID.' edited by User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('File-Link', $msg);
        
        $this->render('success');
    }
    
    //  Submit the custom instructions for a link
    public function submitInstructions($linkID)
    {
        $model = $this->model('fileLinks');
        
        $model->updateLinkInstruction($linkID, $_POST['custom-note']);
        
        //  Note the change in the log files
        $msg = 'File Link ID: '.$linkID.' instructions updated by User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('File-Link', $msg);
        
        $this->render('success');
    }
    
    //  Load the custom instructions for a note
    public function loadInstructions($linkID)
    {
        $model = $this->model('fileLinks');
        $data = $model->getLinkInstructions($linkID);
        
        $this->render($data->instruction);
    }
    
    //  Load the share link form
    public function shareLinkForm()
    {
        $model = $this->model('users');
        $userList = $model->getUserList();
        
        $optList = '<option></option>';
        foreach($userList as $user)
        {
            $optList .= '<option value="'.$user->user_id.'">'.$user->first_name.' '.$user->last_name.'</option>';
        }
        
        $data['optList'] = $optList;
        
        $this->view('links/share_link_form', $data);
        $this->render();
    }
    
    //  Subit the share link form
    public function shareLinkSubmit($linkID)
    {
        $msg = Template::getUserName($_SESSION['id']).' has shared a file link with you.';
        $link = '/links/details/'.$linkID;
        $user = $_POST['selectUser'];
        
        Template::notifyOneUser($msg, $link, $user);
        
        //  Note the change in the log files
        $msg = 'File Link ID: '.$linkID.' shared with ('.$user.')'.Template::getUserName($user).' by User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('File-Link', $msg);
        
        $this->render('success');
    }
    
    //  Load the email link form
    public function emailLinkForm($linkID)
    {
        $model = $this->model('fileLinks');
        $link = $model->getLinkDetails($linkID);
        
        $data['link'] = Config::getCore('baseURL').'file-link/'.$link->link_hash;
        $this->view('links/email_form', $data);
        $this->render();
    }
    
    //  Submit the email link form
    public function submitEmailLink()
    {
        Email::init();
        Email::addSubject('Tech Bench Notification:  New File Link');
        Email::addUser($_POST['email']);
        Email::addBody($_POST['message']);
        Email::send();
        
        $this->render('success');
    }
}
