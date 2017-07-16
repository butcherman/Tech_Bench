<?php
/*
|   Tips controller is for all tech tips/user Knowledge Base
*/
class Tips extends Controller
{
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
    
    //  Landing page shows tech tip search page
    public function index()
    {
        $this->view('tips.search');
        $this->template('techUser');
        $this->render();
    }
    
    //  Add a new tech tip
    public function newTip()
    {
        $model = $this->model('systems');
        
        $data['optList'] = '';
        $cats = $model->getCategories();
        foreach($cats as $cat)
        {
            $data['optList'] .= '<optgroup label="'.strtoupper($cat->description).'">';
            $systems = $model->getSystems($cat->description);
            foreach($systems as $sys)
            {
                $data['optList'] .= '<option value="'.$sys->name.'">'.$sys->name.'</option>';
            }
            $data['optList'] .= '</optgroup>';
        }
        
        $this->view('tips.new', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to submit a new tech tip
    public function newTipSubmit()
    {
        $model = $this->model('techTips');
        
        //  Add the tip information into the database
        $tags = isset($_POST['sysTags']) ? $_POST['sysTags'] : '';
        $tipID = $model->createTip($_POST['subject'], $_POST['tipData'], $_SESSION['id'], $tags);
        
        //  If there are any files, add them as part of the tech tip
        if(isset($_FILES) && !empty($_FILES))
        {
            $fileModel = $this->model('files');
            $path = Config::getFile('uploadRoot').Config::getFile('tipPath').$tipID;
            $fileModel->createFolder($path);
            $fileModel->setFileLocation($path.Config::getFile('slash'));
            $fileID = $fileModel->processFiles($fileModel->reArrayFiles($_FILES['file']), $_SESSION['id'], 'tech');
            foreach($fileID as $id)
            {
                $model->insertTipFile($tipID, $id);
            }
        }
        
        //  Write a log to note the tech tip
        $msg = 'New Tech Tip Subject: '.$_POST['subject'].' added by USER ID: '.$_SESSION['id'];
        Logs::writeLog('Tech-Tips', $msg);
        
        //  Create a notification for the dashboard
        $msg = 'New Tech Tip Created - '.$_POST['subject'];
        $lnk = '/tips/id/'.$tipID.'/'.str_replace(' ', '-', $_POST['subject']);
        Template::notifyAllUsers($msg, $lnk);
        
        //  Email all users about the tech tip
        $tipData = $model->getTipData($tipID);        
        
        $data = [
            'baseURL' => Config::getCore('baseURL'),
            'title' => $tipData->title,
            'tipID' => $tipData->tip_id,
            'date' => $tipData->added_on,
            'author' => Template::getUserName($tipData->user_id),
            'tip' => $tipData->details
        ];
        $emBody = $this->emailView('tips.newTechTip', $data);
        Email::init();
        $emailAddresses = Email::getAddresses('em_tech_tip');
        Email::addSubject('Tech Bench Notification: New Tech Tip');
        Email::addUser($emailAddresses);
        Email::addBody($emBody);
        Email::send();
        
        $this->render($tipID);
    }
}