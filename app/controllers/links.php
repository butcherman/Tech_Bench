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
        $this->template('techUser');
        $this->view('links.home');
        $this->render();
    }
    
    //  Create function allows users to create a new file link for users
    public function create()
    {
        $this->template('techUser');
        $this->view('links.new');
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
            $fileID = $fileModel->processFiles($_FILES, $_SESSION['id'], 'open');
            foreach($fileID as $id)
            {
                $model->insertLinkFile($linkID, $id, $_SESSION['id']);
            }
        }
        
        $msg = 'New File Link ID: '.$linkID.' created by User ID: '.$_SESSION['id'];
        Logs::writeLog('File-Link', $msg);
        
        $this->render($linkID);
    }
    
    //  Details page shows the details for a specific link
    public function details($linkID)
    {
        $this->template('techUser');
//        $this->view('links.new');
        $this->render();
    }
}