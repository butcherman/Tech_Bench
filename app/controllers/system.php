<?php
/*
|   System controller allows user to access documents and files for each system type in the databse
*/
class System extends Controller
{
    public function __construct()
    {
        Security::setPageLevel('tech');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
        }
        $this->template('techUser');
    }
    
    //  Index function will determine the category and system type being called and display the information for that system
    public function index($cat = '', $sys = '')
    {
        if(!empty($cat) && !empty($sys))
        {
            $this->showSystemData($cat, $sys);
        }
        else if(!empty($cat) && empty($sys))
        {
            $this->showSystems($cat);
        }
        else
        {
            $this->showCategories();
        }
        
        $this->render();
    }
    
    //  Show categories function will list all of the available categories 
    private function showCategories()
    {
        $model = $this->model('systems');
        $catList = $model->getCategories();
        
        $data['header'] = 'Select A System Category';
        $data['list'] = '';
        
        foreach($catList as $cat)
        {
            $data['list'] .= '<li class="list-group-item"><a href="/system/'.str_replace(' ', '-', $cat->description).'" class="btn btn-default btn-block">'.strtoupper($cat->description).'</a></li>';
        }
        
        $this->view('system.systemList', $data);
    }
    
    //  Show the system types for a specific category
    private function showSystems($cat)
    {
        $model = $this->model('systems');
        $sysList = $model->getSystems($cat);
        
        $data['header'] = 'Select A System';
        $data['list'] = '';
        
        foreach($sysList as $sys)
        {
            $data['list'] .= '<li class="list-group-item"><a href="/system/'.str_replace(' ', '-', $cat).'/'.str_replace(' ', '-', $sys->name).'" class="btn btn-default btn-block">'.strtoupper($sys->name).'</a></li>';
        }
        
        $this->view('system.systemList', $data);
    }
    
    //  Show system information for the selected system
    private function showSystemData($cat, $sys)
    {
        $model = $this->model('systems');
        
        //  Verify that the system is valid
        if(!($sysID = $model->getSysID($sys)))
        {
            $msg = 'User: '.$_SESSION['id'].' attempted to access an invalid system: '.$_GET['url'];
            Logs::writeLog('Invalid', $msg);
            header('Location: /err/invalid-system');
            die();
        }
        
        //  Get the types of files that are saved for systems
        $fileTypes = $model->getFileTypes();
        $fileList = '<ul class="nav nav-tabs">';
        $fileData = '<div class="tab-content">';
        $optList  = '';
        $i = 0;
        foreach($fileTypes as $type)
        {
            $fileList .= '<li ';
            $fileList .= $i == 0 ? 'class="active">' : '>';
            $fileList .= '<a href="#'.str_replace(' ', '-', $type->description).'" data-toggle="tab">'.$type->description.'</a></li>';

            $fileList .= '<div id="'.str_replace(' ', '-', $type->description).'"class="tab-pane table-responsive ';
            $fileList .= $i == 0 ? 'active">' : 'fade">';
            $fileList .= '<table class="table table-striped ajax-table" data-load="'.str_replace(' ', '-', $type->description).'"><thead><tr><th>File</th><th>Added By</th><th>Date Added</th></tr></thead><tbody><tr><td colspan="4" class="text-center">No Files</td></tr></tbody></table></div>';

            $optList .= '<option value="'.str_replace(' ', '-', $type->description).'">'.$type->description.'</option>';

            $i++;
        }
        $fileList .= '</ul>';
        $dataList .= '</div>';
        
        
        //  Create view data
        $data['sysName'] = $sys;
        $data['fileList'] = $fileList;
        $data['dataList'] = $dataList;
        $data['optList']  = $optList;
        
        ////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////
        /////////////////////////////////////
        
        $this->view('system.systemData', $data);
    }
}










