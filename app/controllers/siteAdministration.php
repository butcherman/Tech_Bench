<?php
/*
|   SiteAdmistration controller allows for adding systems and other global administration.
|   User and other basic administrative functions are done in the standard admin controller
*/

class siteAdministration extends Controller
{
    public function __construct()
    {
        Security::setPageLevel('site admin');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
    }
    
    //  Landing page for the Site Administrator
    public function index()
    {
        $this->view('admin.site.home');
        $this->template('techUser');
        $this->render();
    }
    
    //  Add a new system category
    public function createCategory()
    {
        $this->view('admin.site.newCategory');
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to submit the new category
    public function submitNewCategory()
    {
        //  Insert the category into the databse
        $model = $this->model('siteAdmin');
        $model->createCategory($_POST['category']);
        
        //  Create the category folder
        $path = Config::getFile('uploadRoot').Config::getFile('sysPath').$_POST['category'];
        $fileMod = $this->model('files');
        $fileMod->createFolder($path);
        
        $this->render('success');
    }
    
    //  Modify an existing system category
    public function modifyCategory()
    {
        $model = $this->model('systems');
        $cats = $model->getCategories();
        
        $data['categories'] = '';
        foreach($cats as $cat)
        {
            $data['categories'] .= '<option value="'.$cat->description.'">'.$cat->description.'</option>';
        }
        
        $this->view('admin.site.editCategory', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to submit the new category
    public function submitUpdateCategory()
    {
        $model = $this->model('siteAdmin');
        $sysModel = $this->model('systems');
        
        //  Get the folders that are in the category folder
        $folders = [];
        $systems = $sysModel->getSystems($_POST['selectCategory']);
        foreach($systems as $sys)
        {
            $folders[] = $model->getSystemFolder($sys->name);
        }
        
        //  Update the category name in the database
        $catID = $sysModel->getCategoryID($_POST['selectCategory']);
        $model->updateCategory($catID, $_POST['category']);
        
        //  Build new folder structure with the new category name and all of the folders within it.
        //  Note:  old folder structure will stil exist as files will remain in those folders
        $fileModel = $this->model('files');
        $path = Config::getFile('uploadRoot').Config::getFile('sysPath').$_POST['category'];
        $fileModel->createFolder($path);
        foreach($folders as $folder)
        {
            $fileModel->createFolder($path.Config::getFile('slash').$folder);
        }
        
        $this->render('success');
    }
    
    //  Add a new system type under an existing category
    public function createSystem()
    {
        $model = $this->model('systems');
        $cats = $model->getCategories();
        
        $data['categories'] = '';
        foreach($cats as $cat)
        {
            $data['categories'] .= '<option value="'.$cat->description.'">'.$cat->description.'</option>';
        }
        
        $this->view('admin.site.newSystem', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to submit the new system type
    public function createSystemSubmit()
    {
        $model = $this->model('siteAdmin');
        
        //  Create the system in the database
        $folder = $model->createSystem($_POST['category'], $_POST['sysName'], $_POST);
        
        //  Create the system folder
        $path = Config::getFile('uploadRoot').Config::getFile('sysPath').$_POST['category'].Config::getFile('slash').$folder;
        $fileMod = $this->model('files');
        $fileMod->createFolder($path);
        
        $this->render('success');
    }
    
    //  Edit an existing system type under an existing category
    public function modifySystem()
    {
        $model = $this->model('systems');
        $cats = $model->getCategories();
        
        $data['categories'] = '';
        foreach($cats as $cat)
        {
            $data['categories'] .= '<option value="'.str_replace(' ', '_', $cat->description).'">'.$cat->description.'</option>';
        }
        
        $this->view('admin.site.editSystem', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to reload the navigation links
    public function reloadSysLinks()
    {
        $this->render(Template::getSysLinks());
    }
    
    //  Ajax call to load an option group with system names based on category passed in
    public function loadSysNames($category)
    {
        $model = $this->model('systems');
        $systems = $model->getSystems(str_replace('_', ' ', $category));
        
        $sysList = '<option></option>';
        foreach($systems as $sys)
        {
            $sysList .= '<option value="'.$sys->name.'">'.$sys->name.'</option>';
        }
        
        $this->render($sysList);
    }
    
    //  Ajax call to load the existing system table information
    public function loadSysData($sysName)
    {
        $model = $this->model('systems');
        $table = $model->getSysTable($model->getSysID($sysName));
        $cols = $model->getCols($table);
        
        $i = 1;
        $sysData = '<legend class="text-center">Customer Information To Gather</legend>';
        foreach($cols as $col)
        {
            if($col->COLUMN_NAME != 'data_id' && $col->COLUMN_NAME != 'cust_id')
            {
                $sysData .= '<div class="form-group"><label for="col'.$i.'">System Data:</label><input type="text" name="col'.$i.'" id="col'.$i.'" class="form-control" value="'.str_replace('_', ' ',$col->COLUMN_NAME).'" /></div>';
                $i++;
            }
        }
        $sysData .= '<script>var numRows = '.$i.'</script>';
        
        $this->render($sysData);
    }
}
