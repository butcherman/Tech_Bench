<?php
/*
|   My files controller allows registered users the ability to store their own files specifically for 
|   them to access.  These files are not shared or accessable by anyone else.
*/

class MyFiles extends Controller
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
    
    //  Landing page shows all the files
    public function index()
    {
        $this->view('user/user_my_files_index');
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to load the files for the user
    public function loadFiles($userID)
    {
        $model = $this->model('companyForms');
        $files = $model->getUserFiles($_SESSION['id']);
        
        if(!$files)
        {
            $content = '<h4 class="text-center">No Files</h4>';
        }
        else
        {
            $i = 0;
            $r = 6;
            $content = '<div class="row top-buffer">';
            
            foreach($files as $file)
            {
                $fileData = new SplFileInfo($file->file_name);
                $ext = $fileData->getExtension();
                
                $base = '/source/img/file_icons/';
                if(file_exists('../public/source/img/file_icons/'.$ext.'.png'))
                {
                    $src = '/source/img/file_icons/'.$ext.'.png';
                }
                else
                {
                    $src = '/source/img/file_icons/_blank.png';
                }
                
                if($i % $r == 0 && $i != 0)
                {
                    $content .= '</div><div class="row">';
                }
                
                $content .= '<div class="col-xs-6 col-md-2"><div class="thumbnail"><a href="/download/'.$file->file_id.'/'.$file->file_name.'"><img src="'.$src.'" alt="File" /><div class="caption text-center"><strong>'.$file->name.'</strong></div></div></div>';
                
                $i++;
            }
            
            $content .= '</div>';
        }
        
        $this->render($content);
    }
    
    //  Ajax call to submit a new file
    public function newFileSubmit()
    {
        $model = $this->model('companyForms');
        $fileModel = $this->model('files');
        
        $path = Config::getFile('uploadRoot').Config::getFile('userPath');
        $fileModel->setFileLocation($path);
        $fileID = $fileModel->processFiles($_FILES, $_SESSION['id'], 'tech');
        
        $model->addUserFile($_SESSION['id'], $_POST['name'], $fileID[0]);
        
        //  Log the change in the log file
        $msg = 'New User File Loaded - '.$_POST['name'].' by ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('User-Files', $msg);
        
        $this->render('success');
    }
}
