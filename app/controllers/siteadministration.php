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
        $this->view('site_admin/index');
        $this->template('techUser');
        $this->render();
    }
    
    //  Add a new system category
    public function createCategory()
    {
        $this->view('site_admin/category_new');
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
        $path = Config::getFile('uploadRoot').Config::getFile('sysPath').str_replace(' ', '_', $_POST['category']);
        $fileMod = $this->model('files');
        $fileMod->createFolder($path);
        
        //  Note the change in the log files
        $msg = 'User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']).' created a new system category '.$_POST['category'];
        Logs::writeLog('Administration-Change', $msg);
        
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
        
        $this->view('site_admin/category_edit', $data);
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
        
        //  Note the change in the log files
        $msg = 'User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']).' modified a system category '.$_POST['category'];
        Logs::writeLog('Administration-Change', $msg);
        
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
        
        $this->view('site_admin/system_new', $data);
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
        
        //  Note the change in the log files
        $msg = 'User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']).' created a new system  '.$_POST['sysName'];
        Logs::writeLog('Administration-Change', $msg);
        
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
        
        $this->view('site_admin/system_edit', $data);
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
    
    //  Global system settings for what features are allowed and what are not
    public function systemSettings()
    {
        $data = [
            'links' => Config::getSetting('allow_upload_links') ? ' checked' : '',
            'forms' => Config::getSetting('allow_company_forms') ? ' checked' : '',
            'files' => Config::getSetting('allow_my_files') ? ' checked' : ''
        ];
        
        $this->view('site_admin/settings_global', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Submit the change settings form
    public function submitSettingsForm()
    {
        //  Determine if the users can access the File upload Links section
        if(isset($_POST['fileLinks']) && $_POST['fileLinks'] === 'on')
        {
            if(!Config::getSetting('allow_upload_links'))
            {
                Config::updateSetting('allow_upload_links', 1);
            }
        }
        else
        {
            if(Config::getSetting('allow_upload_links'))
            {
                Config::updateSetting('allow_upload_links', 0);
            }
        }
        
        //  Determine if the users can access the Company Forms section
        if(isset($_POST['companyForms']) && $_POST['companyForms'] === 'on')
        {
            if(!Config::getSetting('allow_company_forms'))
            {
                Config::updateSetting('allow_company_forms', 1);
            }
        }
        else
        {
            if(Config::getSetting('allow_company_forms'))
            {
                Config::updateSetting('allow_company_forms', 0);
            }
        }
        
        //  Determine if the users can access the My Files section
        if(isset($_POST['myFiles']) && $_POST['myFiles'] === 'on')
        {
            if(!Config::getSetting('allow_my_files'))
            {
                Config::updateSetting('allow_my_files', 1);
            }
        }
        else
        {
            if(Config::getSetting('allow_my_files'))
            {
                Config::updateSetting('allow_my_files', 0);
            }
        }
        
        //  Note the change in the log files
        $msg = 'User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']).' modified the global system settings';
        Logs::writeLog('Administration-Change', $msg);
        
        $this->render('success');
    }
    
    //  Modify the email settings form
    public function emailSettings()
    {
        $this->view('site_admin/email_settings_form');
        $this->template('techUser');
        $this->render();
    }
    
    //  Submit the email settings form
    public function emailSettingsSubmit()
    {
        Config::updateSetting('email_from', $_POST['emAddr']);
        Config::updateSetting('email_host', $_POST['emHost']);
        Config::emailPassword($_POST['emPass']);
        Config::updateSetting('email_port', $_POST['emPort']);
        Config::updateSetting('email_user', $_POST['emUser']);
        
        //  Note the change in the log files
        $msg = 'User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']).' modified the email settings';
        Logs::writeLog('Administration-Change', $msg);
        
        $this->render('success');
    }
    
    //  Company logo and company information form
    public function companySettings()
    {
        $this->view('site_admin/logo_new');
        $this->template('techUser');
        $this->render();
    }
    
    //  Submit the update company information form
    public function submitCompanySettings()
    {
        //  Get all values of the current config file
        $config = Config::getWholeConfig();
        foreach($config as $category)
        {
            foreach($category as $item => $value)
            {
                $_SESSION['setupData'][$item] = $value;
            }
        }
        
        $_SESSION['setupData']['baseURL'] = $_POST['siteURL'];
        $_SESSION['setupData']['title'] = $_POST['title'];
        
        //  Rewrite the config file
        ob_start();
            require __DIR__.'/../views/setup/setup.defaultConfig.php';
        $configFile = ob_get_clean();
        $configPath = __DIR__.'/../../config/config.ini';
        file_put_contents($configPath, $configFile, LOCK_EX);
        
        $this->render('success');
    }
    
    //  Submit a new company logo
    public function submitNewLogo()
    {
        $fileModel = $this->model('files');
        
        $filePath = __DIR__.'/../../public/source/img/';
        
        $file = $_FILES['newLogo'];
        
        //  Determine if there is an error in the file
        if($file['error'] > 0)
        {
            $msg = 'FILE '.$file['name'].' failed to upload by user '.$userID.'. Error '.$file['error'];
            Logs::writeLog('File-Error', $msg);
            $success = false;
        }
        //  Determine if the file is too big
        else if($file['size'] > Config::getFile('maxUpload'))
        {
            $msg = 'File '.$file['name'].' failed to upload by user '.$userID.'. Error: File is too big '.$file['size'];
            Logs::writeLog('File-Error', $msg);
            $success = false;
        }
        //  No errors, process file
        else
        {
            $fileName = $file['name'];

            //  Make sure that the destination folder is writable
            if(is_dir($filePath) && is_writable($filePath))
            {
                //  Move the file to the proper location
                if(move_uploaded_file($file['tmp_name'], $filePath.$fileName))
                {
                    $success = true;
                }
                else
                {
                    $success = false;
                    $msg = 'Unable to Upload File '.$fileName.'. Unable to move file to folder '.$filePath.'.';
                    Logs::writeLog('File-Error', $msg);
                }
            }
            else
            {
                $success = false;
                $msg = 'Unable to Upload File '.$fileName.'. Folder '.$this->fileLocation.' does not exist or cannot be written to.';
                Logs::writeLog('File-Error', $msg);
            }
        }
        
        //  Get all values of the current config file
        $config = Config::getWholeConfig();
        foreach($config as $category)
        {
            foreach($category as $item => $value)
            {
                $_SESSION['setupData'][$item] = $value;
            }
        }
        
        $_SESSION['setupData']['logo'] = $fileName;
        
        //  Rewrite the config file
        ob_start();
            require __DIR__.'/../views/setup/setup.defaultConfig.php';
        $configFile = ob_get_clean();
        $configPath = __DIR__.'/../../config/config.ini';
        file_put_contents($configPath, $configFile, LOCK_EX);
        
        $this->render('success');
    }
    
    //  Modify the types of file that can be saved for the systems - Note: these are global for all systems
    public function systemFileTypes()
    {
        $model = $this->model('systems');
        $fileTypes = $model->getFileTypes();
        
        $types = '';
        foreach($fileTypes as $file)
        {
            $types .= '<li class="list-group-item text-center"><a href="#edit-modal" class="edit-type-link" data-toggle="modal" data-value="'.$file->type_id.'">'.$file->description.'</a></li>';
        }
        
        $data = [
            'fileTypes' => $types
        ];
        
        $this->view('site_admin/system_file_types_list', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Submit the new file type form
    public function submitNewFileType()
    {
        $model = $this->model('siteAdmin');
        $model->addSysFileType($_POST['typeName']);
        
        $this->render('success');
    }
    
    //  Load the edit file type form
    public function editFileTypeForm($typeID)
    {
        $model = $this->model('systems');
        $desc = $model->getAFileType($typeID)->description;
        
        $data = [
            'description' => $desc,
            'typeID' => $typeID
        ];
        
        $this->view('site_admin/system_file_types_edit', $data);
        $this->render();
    }
    
    //  Submit the edit file type form
    public function submitEditFileType($typeID)
    {
        $model = $this->model('siteAdmin');
        $model->editSysFileType($_POST['typeName'], $typeID);
        
        $this->render('success');
    }
    
    //  Delete a file type 
    public function deleteFileType($typeID)
    {
        $model = $this->model('siteAdmin');
        $success = $model->delSysFileType($typeID);
        
        $result = $success ? 'success' : 'failed';
        
        $this->render($result);
    }
}
