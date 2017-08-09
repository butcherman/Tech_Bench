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
        
        $this->view('tips.search', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to search for a tech tip
    public function searchTips()
    {
        $model = $this->model('techTips');
        
        $tipData = $model->searchTips($_POST['keyword'], $_POST['systemType']);
        
        $tipList = '';
        if(!$tipData)
        {
            $tipList = '<tr><td colspan="2" class="text-center"><h3>No Results</h3></tr></tr>';
        }
        else
        {
            foreach($tipData as $tip)
            {
                $tipList .= '<tr><td><a href="/tips/id/'.$tip->tip_id.'/'.str_replace(' ', '-', $tip->title).'">'.$tip->title.'</a></td><td>'.date('M j, Y', strtotime($tip->added_on)).'</td></tr>';
            }
        }
        
        $this->render($tipList);
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
    
    //  Display tech tip details
    public function id($tipID)
    {
        $model = $this->model('techTips');
        $tipData = $model->getTipData($tipID);
        
        //  Determine if a valid tip has been selected
        if(!$tipData)
        {
            $this->view('tips.badID');
        }
        else
        {
            //  Get the system tags associated with the tech tip
            $tipTagsArr = $model->getTipTags($tipID);
            $tipTags = '';
            foreach($tipTagsArr as $tag)
            {
                $tipTags .= '<div class="tech-tip-tag" name="tipTag[]">'.$tag->name.'</div>';            
            }
            
            //  Get the files associated with the tech tip
            $tipFilesArr = $model->getTipFiles($tipID);
            if(!$tipFilesArr)
            {
                $tipFiles = '<h4>No Files</h4>';
            }
            else
            {
                $tipFiles = '';
                foreach($tipFilesArr as $file)
                {
                    $tipFiles .= '<li><a href="/download/'.$file->file_id.'/'.$file->file_name.'">'.$file->file_name.'</a></li>';
                }
            }

            $data = [
                'title' => $tipData->title,
                'tipID' => $tipData->tip_id,
                'author' => Template::getUserName($tipData->user_id),
                'date' => date('M j, Y', strtotime($tipData->added_on)),
                'tip' => $tipData->details,
                'tipFav' => $model->isTipFav($tipID, $_SESSION['id']) ? 'item-fav-checked' : 'item-fav-unchecked',
                'tipTags' => $tipTags,
                'files' => $tipFiles,
                'editLink' => '',
                'deleteLink' => ''
            ];
            
            //  Determine if the user has access to the "Edit Tip Link"
            if(!empty(Template::getAdminLinks()))
            {
                $data['editLink'] = '<a href="/tips/edit-tip/'.$tipData->tip_id.'/'.str_replace(' ', '-', $tipData->title).'" class="btn btn-default btn-block">Edit This Tech Tip</a>';
                $data['deleteLink'] = '<a href="/tips/delete-tip/'.$tipData->tip_id.'/'.str_replace(' ', '-', $tipData->title).'" class="btn btn-default btn-block">Delete This Tech Tip</a>';
            }

            $this->view('tips.tipDetails', $data);
        }
        
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to toggle whether the tip is in the users favorites list
    public function toggleFav($tipID)
    {
        $model = $this->model('techTips');
        
        if($model->isTipFav($tipID, $_SESSION['id']))
        {
            $model->removeTipFav($tipID, $_SESSION['id']);
        }
        else
        {
            $model->addTipFav($tipID, $_SESSION['id']);
        }
    }
    
    //  Load the Edit Tip form
    public function editTip($tipID)
    {
        //  Reset page security to Administrator
        Security::setPageLevel('admin');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
        
        $tipModel = $this->model('techTips');
        $model = $this->model('systems');
        $tipData = $tipModel->getTipData($tipID);
        
        $optList = '';
        $cats = $model->getCategories();
        foreach($cats as $cat)
        {
            $optList .= '<optgroup label="'.strtoupper($cat->description).'">';
            $systems = $model->getSystems($cat->description);
            foreach($systems as $sys)
            {
                $optList .= '<option value="'.$sys->name.'">'.$sys->name.'</option>';
            }
            $optList .= '</optgroup>';
        }
        
        
        //  Get the system tags associated with the tech tip
        $tipTagsArr = $tipModel->getTipTags($tipID);
        $tipTags = '';
        foreach($tipTagsArr as $tag)
        {
            $tipTags .= '<div class="tech-tip-tag" name="tipTag[]" data-value="'.$tag->name.'">'.$tag->name.' <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';            
        }

        //  Get the files associated with the tech tip
        $tipFilesArr = $tipModel->getTipFiles($tipID);
        if(!$tipFilesArr)
        {
            $tipFiles = '<h4>No Files</h4>';
        }
        else
        {
            $tipFiles = '';
            foreach($tipFilesArr as $file)
            {
                $tipFiles .= '<li class="delete-file pointer" data-fileid="'.$file->file_id.'"><span class="glyphicon glyphicon-trash"></span> '.$file->file_name.'</li>';
            }
        }

        $data = [
            'title' => $tipData->title,
            'tipID' => $tipData->tip_id,
            'author' => Template::getUserName($tipData->user_id),
            'date' => date('M j, Y', strtotime($tipData->added_on)),
            'tip' => $tipData->details,
            'tipTags' => $tipTags,
            'files' => $tipFiles,
            'optList' => $optList,
            'editLink' => ''
        ];
    
        $this->view('tips.edit', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to remove a file attached to a tech tip
    public function removeFIle($fileID)
    {   
        $model = $this->model('techTips');
        $fileModel = $this->model('files');
        
        if($fileModel->deleteFile($fileID))
        {
            $model->deleteFile($fileID);
        }
        
        $this->render('success');
    }
    
    //  Ajax call to submit editing a tech tip
    public function editTipSubmit($tipID)
    {
        $model = $this->model('techTips');
        
        //  Add the tip information into the database
        $tags = isset($_POST['sysTags']) ? $_POST['sysTags'] : [];
        $model->updateTip($tipID, $_POST['subject'], $_POST['tipData'], $tags);
        
        //  If there are any files, add them as part of the tech tip
        if(isset($_FILES) && !empty($_FILES))
        {
            $fileModel = $this->model('files');
            $path = Config::getFile('uploadRoot').Config::getFile('tipPath').$tipID;
            $fileModel->setFileLocation($path.Config::getFile('slash'));
            $fileID = $fileModel->processFiles($fileModel->reArrayFiles($_FILES['file']), $_SESSION['id'], 'tech');
            foreach($fileID as $id)
            {
                $model->insertTipFile($tipID, $id);
            }
        }
        
        //  Write a log to note the tech tip
        $msg = 'Updated Tech Tip: '.$_POST['subject'].', Tip ID: '.$tipID.' updated by USER ID: '.$_SESSION['id'];
        Logs::writeLog('Tech-Tips', $msg);
        
        $this->render($tipID);
    }
    
    //  Load the delete tip page
    public function deleteTip($tipID)
    {
        //  Reset page security to Administrator
        Security::setPageLevel('admin');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
        
        $data['tipID'] = $tipID;
        $this->view('tips.deleteTip', $data);
        $this->template('techuser');
        $this->render();
    }
    
    //  Delete a tech tip
    public function deleteTipSubmit($tipID)
    {
        $model = $this->model('techTips');
        
        $files = $model->getTipFiles($tipID);
        if(!empty($files))
        {
            $fileModel = $this->model('files');
            foreach($files as $file)
            {
                $fileModel->deleteFile($file->file_id);
            }
            $path = Config::getFile('uploadRoot').Config::getFile('tipPath').$tipID;
            $fileModel->deleteFolder($path);
        }
        
        $model->deleteTechTip($tipID);
        
        $this->render('success');
    }
    
    //  Ajax call to Load Tech Tip Comments
    public function getComments($tipID)
    {
        $model = $this->model('techTips');
        $tips = $model->getComments($tipID);
        
        $data = '';
        foreach($tips as $tip)
        {
            $data .= '<tr><td>'.$tip->comment.' - <span class="comment-author">'.Template::getUserName($tip->user_id).' - '.date('M j, Y', strtotime($tip->added_on)).'</span></td></tr>';
        }
        
        $data .= '<tr class="no-border">
                    <td><a href="#" id="add-tip-comment">Add Comment</a></td>
                </tr>
                <tr class="no-border hidden" id="show-comment-form">
                    <td>
                        <form id="tech-tip-comment-form">
                            <textarea id="commentInput" name="commentInput" rows="5"></textarea>
                            <input type="submit" id="submit-comment-form" class="btn btn-default btn-block" value="Add Comment" />
                        </form>
                    </td>
                </tr>';
        
        $this->render($data);
    }
    
    //  Add a comment to a tech tip
    public function addComment($tipID)
    {
        $model = $this->model('techTips');
        
        $model->addTipComment($tipID, $_POST['commentInput'], $_SESSION['id']);
        
        $this->render('success');
    }
}
