<?php
/*
|   Customers controller handles all customer information
*/
class Customer extends Controller
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
    
/****************************************************************************
*                           Search Customer Section                         *
*****************************************************************************/
    
    //  Index function brings up the customer search page
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
        
        $this->template('techUser');
        $this->view('customers.search', $data);
        $this->render();
    }
    
    //  Ajax call to search the customer database based on the information inputed into the search form
    public function searchForm()
    {
        $model = $this->model('customers');
        
        $custData = $model->searchCustomer($_POST['customer'], $_POST['city'], $_POST['systemType']);
        
        $custList = '';
        
        if(!$custData)
        {
            $custList = '<tr><td colspan="4" class="text-center"><h3>No Results</h3></td></tr>';
        }
        else
        {
            foreach($custData as $cust)
            {
                $system = $model->getCustSystem($cust->cust_id);
                $custSys = '';
                foreach($system as $sys)
                {
                    $custSys .= $sys->name.'<br />';
                }
                $backup = $model->hasBackup($cust->cust_id) ? date('m-d-Y', strtotime($model->hasBackup($cust->cust_id)->added_on)) : 'None';
                
                $custList .= '<tr><td><a href="/customer/id/'.$cust->cust_id.'/'.str_replace(' ', '-', $cust->name).'">'.$cust->name.'</a></td><td>'.$cust->city.', '.$cust->state.'</td><td>'.$custSys.'</td><td>'.$backup.'</td>';
            }
        }
        
        $this->template('techUser');
        $this->render($custList);
    }
    
/****************************************************************************
*                       New Customer Add Section                            *
*****************************************************************************/
    
    //  Open the add a new customer form
    public function add()
    {
        $this->view('customers.newCustomerForm');
        $this->template('techUser');
        $this->render();
    }
    
    //  Check to see if the customer ID is already taken
    public function checkID()
    {
        $model = $this->model('customers');
        $result = true;
                
        //  Strip any leading zero's from the customer ID - these cannot be stored in the database and will cause file location errors
        $custID = ltrim($_POST['custID'], '0');
        
        //  Determine if the customer already exists
        if($custData = $model->getCustData($custID))
        {
            $result = 'This Customer ID Already Exists';
        }
        
        $this->render(json_encode($result));
    }
    
    //  Submit the new customer form
    public function addCustomerSubmit()
    {
        $model = $this->model('customers');
        $result = 'failed';
        
        //  Strip any leading zero's from the customer ID - these cannot be stored in the database and will cause file location errors
        $custID = isset($_POST['custID']) ? ltrim($_POST['custID'], '0') : '';
        
        //  Determine if the customer already exists
        if(!empty($custData) && $custData = $model->getCustData($custID))
        {
            $result = 'duplicate';
        }
        else
        {
            $custID = $model->addCustomer($_POST);
            
            $fileModel = $this->model('files');
            $filePath = Config::getFile('uploadRoot').Config::getFile('custPath').$custID;
            $fileModel->createFolder($filePath);
            
            //  Note change in log file
            $msg = 'New Customer ID: '.$custID.' Name: '.$_POST['custName'].' Added By User ( '.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
            Logs::writeLog('Customer-Change', $msg);
            
            $result = 'success';
        }
        
        $this->render($result);
    }
    
/****************************************************************************
*                      Display Customer Data Section                        *
*****************************************************************************/
    
    //  Display customer information for a specific customer
    public function id($custID = '')
    {
        $model = $this->model('customers');
        
        if(!$custData = $model->getCustData($custID))
        {
            $this->view('customers.invalidID');
        }
        else
        {
            $data = [
                'custID' => $custID,
                'custName' => $custData->name,
                'dbaName' => $custData->dba_name,
                'address' => $custData->address.'<br />'.$custData->city.', '.$custData->state.' '.$custData->zip,
                'addrLink' => $custData->address.',+'.$custData->city.',+'.$custData->state.',+'.$custData->zip,
                'custFav' => $model->isCustFav($custID, $_SESSION['id']) ? 'item-fav-checked' : 'item-fav-unchecked'
            ];
            
            $this->view('customers.customerDetails', $data);
        }
        
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to toggle whether the customer is in the users favorites list
    public function toggleFav($custID)
    {
        $model = $this->model('customers');
        
        if($model->isCustFav($custID, $_SESSION['id']))
        {
            $model->removeCustFav($custID, $_SESSION['id']);
            
            //  Note change in log file
            $msg = 'Customer ID: '.$custID.' removed as a favorite for ( '.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
            Logs::writeLog('Customer-Change', $msg);
        }
        else
        {
            $model->addCustFav($custID, $_SESSION['id']);
            
            //  Note change in log file
            $msg = 'Customer ID: '.$custID.' added as a favorite for ( '.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
            Logs::writeLog('Customer-Change', $msg);
        }
    }
    
    //  Load the information to edit the customer data
    public function editCustDataLoad($custID)
    {
        $model = $this->model('customers');
        
        $custData = $model->getCustData($custID);
        $data = [
            'custID' => $custID,
            'custName' => $custData->name,
            'dbaName' => $custData->dba_name,
            'address' => $custData->address,
            'city' => $custData->city,
            'state' => $custData->state,
            'zip' => $custData->zip
        ];
        
        $this->view('customers.editCustomerInformation', $data);
        $this->render();
    }
    
    //  Modify the customer information and submit to the database
    public function editCustDataSubmit($custID)
    {
        $model = $this->model('customers');
        
        $model->updateCustData($custID, $_POST);
        
        $msg = 'Customer ID: '.$custID.' Updated By: '.$_SESSION['id'];
        Logs::writeLog('Customer-Change', $msg);
        
        //  Note change in log files
        $msg = 'Customer ('.$custID.')'.$_POST['name'].' information updated by user ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('Customer-Change', $msg);
        
        $this->render('success');
    }
    
/****************************************************************************
*                          Customer Systems Section                         *
*****************************************************************************/
    
    //  Ajax call to load the customers systems
    public function loadSystems($custID)
    {
        $model = $this->model('customers');
        $sysModel = $this->model('systems');
        
        $custSystems = $model->getCustSystem($custID);
        
        function arangeSystem($custID, $sysName, $model, $sysModel)
        {
            //  Pull all system information
            $sysID = $sysModel->getSysID($sysName->name);
            $table = $sysModel->getSysTable($sysID);
            $cols  = $sysModel->getCols($table);
            $data  = $model->getSysData($table, $cols, $custID);

            //  Sort the information into a readable format
            $content = '<dl class="dl-horizontal"><dt>SYSTEM TYPE:</dt><dd>'.$sysName->name.'</dd>';
            foreach($data as $key => $item)
            {
                $content .= '<dt>'.strtoupper(str_replace('_', ' ', $key)).':</dt><dd>'.$item.'</dd>';
            }
            $content .= '</dl>';
            
            return $content;
        }
        
        //  Determine if there are any systems at all
        if(!$custSystems)
        {
            $content = '<h4>No Systems, Please Add One</h4>';
        }
        //  If there is only one system
        else if(count($custSystems) == 1)
        {   
            $content = arangeSystem($custID, $custSystems[0], $model, $sysModel);
        }
        else
        {
            $i = 0;
            $sysTabs = '<ul class="nav nav-tabs">';
            $sysCont = '<div class="tab-content">';
            foreach($custSystems as $syst)
            {
                $content = arangeSystem($custID, $syst, $model, $sysModel);
                
                $sysTabs .= '<li ';
                $sysTabs .= $i == 0 ? 'class="active">' : '>';
                $sysTabs .= '<a href="#'.str_replace(' ', '-', $syst->name).'" data-toggle="tab">'.$syst->name.'</a></li>';

                $sysCont .= '<div id="'.str_replace(' ', '-', $syst->name).'" class="tab-pane ';
                $sysCont .= $i == 0 ? 'active">' : 'fade">';
                $sysCont .= $content.'</div>';

                $i++;
            }
            
            $sysTabs .= '</ul>';
            $sysCont .= '</div>';
            
            $content = $sysTabs.$sysCont;
        }
        
        $this->render($content);
    }
    
    //  Ajax call to load the form to adjust a customer system
    public function editCustSystemLoad($custID, $sysName)
    {
        $model = $this->model('customers');
        $sysModel = $this->model('systems');
        
        //  Determine if the customer only has one system
        $systems = $model->getCustSystem($custID);
        if(count($systems) == 1)
        {
            $sysName = $systems[0]->name;
        }
        $sysName = str_replace('-', ' ', $sysName);

        //  Pull all system information
        $sysID = $sysModel->getSysID($sysName);
        $table = $sysModel->getSysTable($sysID);
        $cols  = $sysModel->getCols($table);
        $data  = $model->getSysData($table, $cols, $custID);

        //  Sort the information into a readable format
        $content = '<form class="system-edit-form"><div class="form-group"><label for="editSysType">SYSTEM TYPE:</label><input type="text" name="editSysType" id="editSysType" class="form-control" value="'.$sysName.'" readonly /></div>';
        foreach($data as $key => $item)
        {
            $content .= '<div class="form-group"><label for="'.$key.'">'.strtoupper(str_replace('_', ' ', $key)).'</label><input type="text" name="'.$key.'" id="'.$key.'" class="form-control" value="'.$item.'" /></div>';
        }
        $content .= '<input type="submit" id="editCustSystemSubmit" class="form-control btn btn-default" value="Submit" /><button class="btn btn-danger btn-block pad-top" id="delete-system">Delete System</button></form>';
        
        $this->render($content);
    }
    
    //  Ajax call to submit the customers system
    public function editCustSystemSubmit($custID)
    {
        $model = $this->model('customers');
        $sysModel = $this->model('systems');
        
        $custName = $model->getCustData($custID)->name;
        
        $sysType = $_POST['editSysType'];
        if(!$sysID = $sysModel->getSysID($sysType))
        {
            $msg = 'Customer ID '.$custID.' failed to update system.  Error: Bad System Type.';
            Logs::writeLog('Customer-Update-Error', $msg);
            $content = 'failed';
        }
        else
        {
            $table = $sysModel->getSysTable($sysID);
            $cols  = $sysModel->getCols($table);
            
            $model->updateSysData($table, $cols, $custID, $_POST);
            
            //  Note change in log files
            $msg = 'Customer ('.$custID.')'.$custName.' system - '.$sysType.' - updated by user ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
            Logs::writeLog('Customer-Change', $msg);
            
            $content = 'success';
        }
        
        $this->render($content);
    }
    
    //  Ajax call to load the form for a new system
    public function newSystemLoad($custID)
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
                $data['optList'] .= '<option value="'.str_replace(' ', '_', $sys->name).'">'.$sys->name.'</option>';
            }
            $data['optList'] .= '</optgroup>';
        }
        
        $this->view('customers.newSysForm', $data);
        $this->render();
    }
    
    //  Ajax call to submit a new customer system
    public function newSystemSubmit($custID)
    {
        $model = $this->model('customers');
        $sysModel = $this->model('systems');
        
        $custName = $model->getCustData($custID)->name;
        $custSystems = $model->getCustSystem($custID);

        if(!$sysID = $sysModel->getSysID(str_replace('_', ' ', $_POST['addSystemType'])))
        {
            $content = 'Bad System Type';
            $msg = 'Customer ID '.$custID.' failed to add system. Error: Bad System Type - '.$_POST['addSystemType'];
            Logs::writeLog('Customer-Change', $msg);
        }
        else
        {
            $dup = false;
            //  Determine if the customer already has the system type
            if(!empty($custSystems))
            {
                foreach($custSystems as $sys)
                {
                    if($sys->name == $_POST['addSystemType'])
                    {
                        $dup = true;
                        $content = 'duplicate';
                    }
                }
            }
            if(!$dup)
            {
                $sysModel->addSysType($custID, $sysID, $_POST);
                $content = 'success';
                
                //  Note change in log files
                $msg = 'Customer ('.$custID.')'.$custName.' new system - '.$_POST['addSystemType'].' -added by user ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
                Logs::writeLog('Customer-Change', $msg);
            }
        }
         
        $this->render($content);
    }
    
    //  Delete an existing customer system
    public function deleteSystem($custID, $sysName)
    {
        $model = $this->model('systems');
        $sysID = $model->getSysID($sysName);
        $model->delSysType($custID, $sysID);
        
        //  Note the change in the log files 
        $msg = 'Customer system '.$sysName.' deleted for customer ID '.$custID;
        Logs::writeLog('Customer-Change', $msg);
        
        $this->render('success');
    }
    
/****************************************************************************
*                         Customer Contacts Section                         *
*****************************************************************************/
    
    //  Ajax call to pull all customer contacts from the database
    public function loadContacts($custID)
    {
        $model = $this->model('customers');
        
        $contacts = $model->getContacts($custID);
        $data = '';
        if(!$contacts)
        {
            $data = '<tr><td colspan="4" class="text-center">No Contacts Please Add One</td></tr>';
        }
        else
        {
            foreach($contacts as $cont)
            {
                $numbers = $model->getContactPhone($cont->cont_id);
                $numList = '';
                if(!empty($numbers))
                {
                    foreach($numbers as $number)
                    {
                        $numList .= $number->description.' - <a href="tel:'.$number->phone_number.'">'.Template::readablePhoneNumber($number->phone_number).'</a><br />';
                    }
                }

                $data .= '<tr><td>'.$cont->name.'</td><td>'.$numList.'</td><td><a href="mailto:'.$cont->email.'">'.$cont->email.'</a></td><td><a href="#edit-modal" title="Edit Contact" class="edit-contact-link" data-tooltip="tooltip" data-toggle="modal" data-contid="'.$cont->cont_id.'"><span class="glyphicon glyphicon-pencil"></span></a> <a href="#edit-modal" title="Delete Contact" class="delete-contact-link"  data-tooltip="tooltip" data-toggle="modal" data-contid="'.$cont->cont_id.'" class="delete-contact-link"><span class="glyphicon glyphicon-trash"></span></a> <a href="/customer/downloadVCard/'.$cont->cont_id.'" title="Download As VCard" data-tooltip="tooltip"><span class="glyphicon glyphicon-download-alt"></span></a></td></tr>';
            }
        }
        
        $this->render($data);
    }
    
    //  Ajax call to load the new contact form
    public function newContactLoad()
    {
        $model = $this->model('customers');
        $numTypes = $model->getPhoneTypes();
        
        $data['numTypes'] = '';
        foreach($numTypes as $type)
        {
            $data['numTypes'] .= '<option value="'.$type->phone_type_id.'">'.$type->description.'</option>';
        }
        
        $this->view('customers.newContactForm', $data);
        $this->render();
    }
    
    //  Ajax call to submit the new contact form
    public function newContactSubmit($custID)
    {
        $model = $this->model('customers');

        $custName = $model->getCustData($custID)->name;
        //  Add the main contact information
        $contID = $model->addContact($custID, $_POST);
        //  Add each of the phone numbers for the contact
        for($i=0; $i < count($_POST['numType'])+1; $i++)
        {
            if(!empty($_POST['contPhone'][$i]))
            {
                $model->addPhoneNumber($contID, $_POST['numType'][$i], $_POST['contPhone']{$i});
            }
        }

        //  Note change in log files
        $msg = 'Customer ('.$custID.')'.$custName.' new contact - '.$_POST['contName'].' - added by user ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('Customer-Change', $msg);
        
        $this->render('success');
    }
    
    //  Ajax call to load the customer contact edit form
    public function editContactLoad($contID)
    {
        $model = $this->model('customers');
        
        //  Get the type of possible phone numbers
        $numberTypes = $model->getPhoneTypes();
        
        //  Get contact information
        $contactData = $model->getOneContact($contID);
        $contactPhones = $model->getContactPhone($contID);
        
        //  cycle through phone numbers
        if(!empty($contactPhones))
        {
            $phns = '';
            foreach($contactPhones as $phone)
            {
                $numTypes = '';
                foreach($numberTypes as $type)
                {
                    $selected = $type->description == $phone->description ? ' selected' : '';
                    
                    $numTypes .= '<option value="'.$type->phone_type_id.'"'.$selected.'>'.$type->description.'</option>';
                }
                
                $phns .= '<div class="row form-group"><div class="col-sm-3 col-sm-offset-1"><select id="numType" name="numType[]" class="form-control">'.$numTypes.'</select></div><div class="col-sm-7"><input type="tel" name="contPhone[]" class="form-control clearable" placeholder="Phone Number" value="'.$phone->phone_number.'" /><span class="number-clear glyphicon glyphicon-remove-circle"></span></div></div>';
            }
        }
        
        $data = [
            'contID' => $contID,
            'name' => $contactData->name,
            'phone' => '',
            'email' => $contactData->email,
            'phoneNumbers' => $phns,
            'numTypes' => $numTypes
        ];
        $this->view('customers.editContactForm', $data);
        $this->render();  
    }
    
    //  Ajax call to submit the customer contact edit form
    public function editContactSubmit($contID)
    {
        $model = $this->model('customers');
        $model->editContact($contID, $_POST);
        $model->deleteContactPhone($contID);
        
        //  Add each of the phone numbers for the contact
        for($i=0; $i < count($_POST['numType'])+1; $i++)
        {
            if(!empty($_POST['contPhone'][$i]))
            {
                $model->addPhoneNumber($contID, $_POST['numType'][$i], $_POST['contPhone']{$i});
            }
        }
        
        //  Note change in log files
        $msg = 'Customer contact ID '.$contID.' - '.$_POST['contName'].' - updated by user ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('Customer-Change', $msg);
        
        $this->render('success');
    }
    
    //  Ajax call to delete a valid customer contact
    public function deleteContactSubmit($contID)
    {
        $model = $this->model('customers');
        $model->deleteContact($contID);
        
        //  Note change in log files
        $msg = 'Customer contact ID '.$contID.' deleted by user ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('Customer-Change', $msg);
        
        $this->render('success');
    }
    
    //  Ajax call to download a customer contact as a VCard
    public function downloadVCard($contID)
    {        
        VCardDownload::getVCard($contID);
    }
    
/****************************************************************************
*                           Customer Notes Section                          *
*****************************************************************************/
    
    //  Ajax call to load all customer notes
    public function loadNotes($custID)
    {
        $model = $this->model('customers');
        $notes = $model->getAllnotes($custID);
        
        if(empty($notes))
        {
            $content = '<div class="row"><div class="col-sm=12 text-center"><h3>No Notes</h3></div></div>';
        }
        else
        {
            $i = 0; //  Counter to determine how many notes are in the row
            $r = 4; //  Maximum of four notes per row
            $content = '<div class="row">';
            foreach($notes as $note)
            {
                $content .= '<div class="col-md-3 panel-wrapper panel-minimized"><div class="panel panel-'.$note->level.'"><div class="panel-heading" title="Click to Expand" data-tooltip="tooltip"><h5>'.$note->subject.'</h5></span></div><div class="panel-body"><span>'.$note->description.'</span></div><div class="panel-footer"><strong>Updated By: </strong>'.Template::getUserName($note->user_id).'<span class="pull-right">Updated: '.date('M j, Y', strtotime($note->updated)).'</span><div class="text-center"><a href="#edit-modal" data-toggle="modal" class="btn btn-default edit-note" data-noteid="'.$note->note_id.'">Edit Note</a></div><div class="clearfix"></div></div></div></div>';
                
                if($i % $r == 0 && $i != 0)
                {
                    $content .= '</div><div class="row">';
                }
            }
            $content .= '</div>';
        }
        
        $this->render($content);
    }
    
    //  Ajax call to load the new note form
    public function newNoteForm()
    {
        $model = $this->model('customers');
        $noteLevels = $model->getNoteLevels();
        
        $levels = '';
        foreach($noteLevels as $lev)
        {
            $levels .= '<option value="'.$lev->note_level_id.'" class="bg-'.$lev->description.'">'.$lev->description.'</option>';
        }
        $data['levels'] = $levels;
        
        $this->view('customers.newNoteForm', $data);
        $this->render();
    }
    
    //  Submit the new note form
    public function newNoteSubmit($custID)
    {
        $model = $this->model('customers');
        
        $custName = $model->getCustData($custID)->name;
        $model->addNewNote($custID, $_SESSION['id'], $_POST);
        
        //  Note change in log files
        $msg = 'Customer ('.$custID.')'.$custName.' new note - '.$_POST['noteSubject'].' - added by user ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('Customer-Change', $msg);
        
        $this->render('success');
    }
    
    //  Ajax call to load the edit note form
    public function editNoteForm($noteID)
    {
        $model = $this->model('customers');
        $noteLevels = $model->getNoteLevels();
        $noteData = $model->getNote($noteID);
        
        $levels = '';
        foreach($noteLevels as $lev)
        {
            $selected = $lev->description === $noteData->level ? ' selected' : '';
            $levels .= '<option value="'.$lev->note_level_id.'" class="bg-'.$lev->description.'"'.$selected.'>'.$lev->description.'</option>';
        }

        $data = [
            'subject' => $noteData->subject,
            'body' => $noteData->description,
            'levels' => $levels
        ];
        
        $this->view('customers.editNoteForm', $data);
        $this->render();
    }
    
    //  Submit the edit note form
    public function editNoteSubmit($noteID)
    {
        $model = $this->model('customers');
        $model->updateNote($noteID, $_SESSION['id'], $_POST);
        
        $msg = 'User ID: '.$_SESSION['id'].' updated note id: '.$noteID;
        Logs::writeLog('Customer-Change', $msg);
        
        //  Note change in log files
        $msg = 'Customer Note - ID '.$noteID.' - updated by user ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('Customer-Change', $msg);
        
        $this->render('success');
    }

/****************************************************************************
*                           Customer Files Section                          *
*****************************************************************************/
    
    //  Load all customer files
    public function loadFiles($custID)
    {
        $model = $this->model('customers');
        $files = $model->getAllFiles($custID);
        
        if(empty($files))
        {
            $content = '<tr><td colspan="5"><h3 class="text-center">No Files</h3></td></tr>';
        }
        else
        {
            $content = '';
            foreach($files as $file)
            {
                $content .= '<tr><td><a href="/download/'.$file->file_id.'/'.$file->file_name.'">'.$file->name.'</a></td><td>'.$file->description.'</td><td>'.Template::getUserName($file->user_id).'</td><td>'.date('m-d-Y', strtotime($file->added_on)).'</td><td><a href="#edit-modal" class="edit-file-lnk" title="Edit File" data-tooltip="tooltip" data-toggle="modal" data-fileid="'.$file->cust_file_id.'"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;<a href="#edit-modal" class="delete-file" title="Delete File" data-toggle="modal" data-tooltip="tooltip" data-fileid="'.$file->cust_file_id.'"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';
            }
        }
        
        $this->render($content);
    }
    
    //  Load the new file form
    public function newFileForm()
    {
        $model = $this->model('customers');
        $fileTypes = $model->getFileTypes();
        
        $data['types'] = '';
        foreach($fileTypes as $type)
        {
            $data['types'] .= '<option value="'.$type->description.'">'.$type->description.'</option>';
        }
        
        $this->view('customers.newFileForm', $data);
        $this->render();
    }
    
    //  Submit the new file form
    public function newFileSubmit($custID)
    {
        $model = $this->model('customers');
        $fileModel = $this->model('files');
        $success = false;
        
        $custName = $model->getCustData($custID)->name;
        $filePath = Config::getFile('uploadRoot').Config::getFile('custPath').$custID.Config::getFile('slash');

        if(!empty($_FILES))
        {
            $fileModel->setFileLocation($filePath);
            $fileID = $fileModel->processFiles($_FILES, $_SESSION['id'], 'tech');
            
            $fileData = [
                'fileID' => $fileID[0],
                'name' => $_POST['fileName'],
                'type' => $_POST['fileType']
            ];
            
            $model->addFile($custID, $_SESSION['id'], $fileData);
            $success = true;
            
            //  Note change in log file
            $msg = 'New file added for customer ('.$custID.')'.$custName.' File ID: '.$fileID[0].' by user ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
            Logs::writeLog('Customer-Change', $msg);
        }
        
        $this->render($success);
    }
    
    //  Ajax call to load the edit file form
    public function editFileLoad($fileID)
    {
        $model = $this->model('customers');
        $fileData = $model->getFile($fileID);
        $fileTypes = $model->getFileTypes();
        
        $data['name'] = $fileData->name;
        $data['types'] = '';
        foreach($fileTypes as $type)
        {
            if($type->description === $fileData->description)
            {
                $data['types'] .= '<option value="'.$type->description.'" selected>'.$type->description.'</option>';
            }
            else
            {
                $data['types'] .= '<option value="'.$type->description.'">'.$type->description.'</option>';
            }
        }
        
        $this->view('customers.editFileForm', $data);
        $this->render();
    }
    
    //  Submit the edit file form
    public function editFileSubmit($fileID)
    {
        $model = $this->model('customers');
        $model->editFile($fileID, $_POST);
        
        //  Note change in log file
        $msg = 'File edited for customer File ID: '.$fileID.' by user ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('Customer-Change', $msg);
        
        $this->render('success');
    }
    
    //  Submit the delete file link
    public function deleteFileSubmit($fileID)
    {
        $model = $this->model('customers');
        $fileData = $model->getFile($fileID);
        
        $fileModel = $this->model('files');
        if($fileModel->deleteFile($fileData->file_id))
        {
            $model->deleteFile($fileID);
            
            //  Note change in log file
            $msg = 'File deleted for customer File ID: '.$fileID.' by user ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
            Logs::writeLog('Customer-Change', $msg);
        }
        
        $this->render('success');
    }
    
    /****************************************************************************
    *                          Linked Customers Section                         *
    *****************************************************************************/

    //  Load all linked sites for this customer
    public function loadLinkedSites($custID)
    {
        $model = $this->model('customers');
        $linkedSites = $model->getLinkedSites($custID);
        
        $linkedView = '';
        foreach($linkedSites as $site)
        {
            if($site->parent_id == $site->cust_id)
            {
                $custName = str_replace(' ', '-', $model->getCustData($site->cust_id)->name);
                $linkedView .= '<div class="col-md-3 col-xs-12"><a href="customer/id/'.$site->cust_id.'/'.$custName.'" class="bookmark"><div class="well bookmark text-center"><strong>Parent Site</strong><br />'.$custName.'</div></a></div>';
            }
            else
            {
                $custName = str_replace(' ', '-', $model->getCustData($site->cust_id)->name);
                $linkedView .= '<div class="col-md-3 col-xs-12"><a href="customer/id/'.$site->cust_id.'/'.$custName.'" class="bookmark"><div class="well bookmark text-center">'.$custName.'</div></a></div>';
            }
        }
        
        if(empty($linkedView))
        {
            $linkedView = '<div class="col-sm-12"><h3 class="text-center">No Linked Sites</h3></div>';
        }
        
        $this->render($linkedView);
    }
    
    //  Load the form to link a site to a parent site
    public function linkSiteForm()
    {
        $this->view('customers.linkCustomerForm');
        $this->render();
    }
    
    //  Check if the parent site entered is actually a valid parent
    public function checkParent()
    {
        $model = $this->model('customers');
        $parentID = $_POST['parentID'];
        $result = $model->checkParentID($parentID);
        
        $data = !$result ? 'This Is Not A Valid Parent ID' : true;
        
        $this->render(json_encode($data));
    }
    
    //  Add the linked sites
    public function submitLinkCustomer($custID)
    {
        $model = $this->model('customers');
        if(isset($_POST['thisIsParent']) && $_POST['thisIsParent'] === 'on')
        {
            $model->addLinkedCustomer($custID, $custID);
        }
        else
        {
            $model->addLinkedCustomer($_POST['customerParent'], $custID);
        }
        
        $this->render('success');
    }
}
