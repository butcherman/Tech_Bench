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
        echo $custID;
        die();
        
        $model->updateCustData($custID, $_POST);
        
        $msg = 'Customer ID: '.$custID.' Updated By: '.$_SESSION['id'];
        Logs::writeLog('Customer-Change', $msg);
        
        render('success');
    }
}
