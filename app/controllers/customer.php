<?php
/*
|   Customers controller handles all customer information
*/
class Customer extends Controller
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
        $this->template('techUser');
    }
    
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
                
                $custList .= '<tr><td><a href="/customer/id/'.$cust->cust_id.'/'.str_replace(' ', '-', $cust->name).'">'.$cust->name.'</a></td><td>'.$cust->city.', '.$cust->state.'</td><td>'.$custSys.'</td>';
            }
        }
        
        $this->render($custList);
    }
    
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
        
        $this->render();
    }
    
    //  Ajax call to toggle whether the customer is in the users favorites list
    public function toggleFav($custID)
    {
        $model = $this->model('customers');
        
        if($model->isCustFav($custID, $_SESSION['id']))
        {
            $model->removeCustFav($custID, $_SESSION['id']);
        }
        else
        {
            $model->addCustFav($custID, $_SESSION['id']);
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
        
        $this->template('');
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
        
        $this->render('success');
    }
    
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
        
  //      $custSystems = $model->getCustSystem($custID);
        
//        function arangeSystem($custID, $sysName, $model, $sysModel)
//        {
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
            $content .= '<input type="submit" id="editCustSystemSubmit" class="form-control btn btn-default" value="Submit" /></form>';
            
//            return $content;
//        }
        
//        //  Determine if there are any systems at all
//        if(!$custSystems)
//        {
//            $content = '<h4>No Systems, Please Add One</h4>';
//        }
//        //  If there is only one system
//        else if(count($custSystems) == 1)
//        {   
//            $content = arangeSystem($custID, $custSystems[0], $model, $sysModel);
//        }
//        else
//        {
//            $i = 0;
//            $sysTabs = '<ul class="nav nav-tabs">';
//            $sysCont = '<div class="tab-content">';
//            foreach($custSystems as $syst)
//            {
//                $content = arangeSystem($custID, $syst, $model, $sysModel);
//                
//                $sysTabs .= '<li ';
//                $sysTabs .= $i == 0 ? 'class="active">' : '>';
//                $sysTabs .= '<a href="#'.str_replace(' ', '-', $syst->name).'" data-toggle="tab">'.$syst->name.'</a></li>';
//
//                $sysCont .= '<div id="'.str_replace(' ', '-', $syst->name).'" class="tab-pane ';
//                $sysCont .= $i == 0 ? 'active">' : 'fade">';
//                $sysCont .= $content.'</div>';
//
//                $i++;
//            }
//            
//            $sysTabs .= '</ul>';
//            $sysCont .= '</div>';
//            
//            $content = $sysTabs.$sysCont;
//        }
        
        $this->render($content);
    }
    
    //  Ajax call to submit the customers system
    public function editCustSystemSubmit($custID)
    {
        $model = $this->model('customers');
        $sysModel = $this->model('systems');
        
        print_r($_POST);
        die();
        
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
            
            $model->updateCustSystem($table, $cols, $custID, $_POST);
            
            $msg = 'Customer ID: '.$custID.' Update System information for '.$sysType.' By User ID: '.$_SESSION['id'];
        }
    }
}
