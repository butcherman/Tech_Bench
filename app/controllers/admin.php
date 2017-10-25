<?php
/*
|   Admin controller handles all basic admin function for the application
*/

class Admin extends Controller
{
    public function __construct()
    {
        Security::setPageLevel('admin');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
    }
    
    //  Landing page shows the administration menu
    public function index()
    {
        $this->view('admin.home');
        $this->template('techUser');
        $this->render();
    }
    
/************************************************************************
*                         User Administration                           *
*************************************************************************/
    
    //  Page to reset a users password for them
    public function resetPassword()
    {
        $model = $this->model('users');
        $userList = $model->getUserList();
        
        $optList = '<option></option>';
        foreach($userList as $user)
        {
            $optList .= '<option value="'.$user->user_id.'">'.$user->first_name.' '.$user->last_name.'</option>';
        }
        
        $data['optList'] = $optList;
        
        $this->view('admin.resetPassword', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to submit the new password
    public function resetPasswordSubmit()
    {
        $model = $this->model('users');

        $model->setPassword($_POST['selectUser'], $_POST['password']);
        if(isset($_POST['forceChange']) && $_POST['forceChange'])
        {
            $model->setForcePasswordChange($_POST['selectUser']);
        }
        
        //  Note change in log files
        $msg = 'User ('.$_POST['selectUser'].')'.Template::getUserName($_POST['selectUser']).' password changed by administrator ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('User-Change', $msg);
        
        $this->render('success');
    }
    
    //  Page to change a users settings for them
    public function changeSettings()
    {
        $model = $this->model('users');
        $userList = $model->getUserList();
        
        $optList = '<option></option>';
        foreach($userList as $user)
        {
            $optList .= '<option value="'.$user->user_id.'">'.$user->first_name.' '.$user->last_name.'</option>';
        }
        
        $data['optList'] = $optList;
        
        $this->view('admin.userSettings', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to load the change user settings form
    public function changeSettingsLoad($userID)
    {
        $model = $this->model('users');
        $userData = $model->getUserData($userID);
        
         $data = [
            'username' => $userData->username,
            'first_name' => $userData->first_name,
            'last_name' => $userData->last_name,
            'email' => $userData->email
        ];
        
        $this->view('admin.userSettingsForm', $data);
        $this->render();
    }
    
    //  Ajax call to submit the user settings
    public function changeSettingsSubmit()
    {
        $model = $this->model('users');
        
        $data = [
            'username' => $_POST['username'],
            'first_name' => $_POST['firstName'],
            'last_name' => $_POST['lastName'],
            'email' => $_POST['email'],
        ];
        
        $model->updateUserData($_POST['selectUser'], $data);
        
        //  Note change in log files
        $msg = 'User ('.$_POST['selectUser'].')'.Template::getUserName($_POST['selectUser']).' settings changed by administrator ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('User-Change', $msg);
        
        $this->render('success');
    }
    
    //  Function to create a new user
    public function newUser()
    {
        $this->view('admin.newUserForm');
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to submit the new user
    public function submitNewUser()
    {
        $model = $this->model('users');
        $model->createUSer($_POST['username'], $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password']);
        
        //  Create the user folder for the My Files section
        $fileModel = $this->model('files');
        $path = Config::getFile('uploadRoot').Config::getFile('userPath');
        $fileModel->createFolder($path);
        
        //  Note change in log files
        $msg = 'New User - '.$_POST['username'].' created by administrator ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('User-Change', $msg);
        
        $this->render('success');
    }
    
    //  Ajax call to check if a username already exists
    public function checkForUser()
    {
        $model = $this->model('users');
        $userList = $model->getUserList();
        
        $valid = true;
        foreach($userList as $user)
        {
            if($user->username === $_POST['username'])
            {
                $valid = false;
                break;
            }
        }
        
        $this->render(json_encode($valid));
    }
    
    //  Deactivate user page
    public function deactivateUser()
    {
        $model = $this->model('users');
        $userList = $model->getUserList();
        
        $optList = '<option></option>';
        foreach($userList as $user)
        {
            $optList .= '<option value="'.$user->user_id.'">'.$user->first_name.' '.$user->last_name.'</option>';
        }
        
        $data['optList'] = $optList;
        
        $this->view('admin.deactivateUser', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to submit deactivating a user
    public function deactivateUserSubmit($userID)
    {
        $model = $this->model('users');
        $model->deactivateUser($userID);
        
        //  Note change in log files
        $msg = 'User ('.$userID.')'.Template::getUserName($userID).' deactivated by administrator ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('User-Change', $msg);
        
        $this->render('success');
    }
    
/************************************************************************
*                      Notifications and Alerts                         *
*************************************************************************/
    
    //  Create a system alert
    public function systemAlert()
    {
        $this->view('admin.newSystemAlert');
        $this->template('techUser');
        $this->render();
    }
    
    //  Submit the system alert form
    public function submitSystemAlert()
    {
        $model = $this->model('siteAdmin');
        $model->addSystemAlert($_POST['message'], $_POST['expire'], $_POST['level']);
        
        //  Note change in log files
        $msg = 'New system alert created by administrator ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('Alert-Message', $msg);
            
        $this->render('success');
    }
    
    //  Create a user specific alert
    public function userAlert()
    {
        $model = $this->model('users');
        $userList = $model->getUserList();
        
        $optList = '';
        foreach($userList as $user)
        {
            $optList .= '<option value="'.$user->user_id.'">'.$user->first_name.' '.$user->last_name.'</option>';
        }
        
        $data['optList'] = $optList;
        
        $this->view('admin.newUserAlert', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Submit the user specific alert
    public function userAlertSubmit()
    {
        $model = $this->model('siteAdmin');
        foreach($_POST['to'] as $userID)
        {
            $model->addUserAlert($_POST['message'], $_POST['expire'], $_POST['level'], $userID, $_POST['dismissable']);
            
            //  Note change in log files
            $msg = 'User alert created for ('.$userID.')'.Template::getUserName($userID).' by administrator ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
            Logs::writeLog('Alert-Message', $msg);
        }
        
        $this->render('success');
    }
    
/************************************************************************
*                     File Links Administration                         *
*************************************************************************/
    
    //  View all links that exist but are expired
    public function expiredLinks()
    {
        $model = $this->model('fileLinks');
        
        $links = $model->getLinks();
        $today = date('Y-m-d');
        $linkList = '';
        
        //  Cycle through the links and pull out expired ones
        foreach($links as $link)
        {
            if($link->expire < $today)
            {
                $numFiles = $model->countLinkFiles($link->link_id);
                
                $linkList .= '<tr><td>'.Template::getUserName($link->user_id).'</td><td><a href="/links/details/'.$link->link_id.'/'.str_replace(' ', '-', $link->link_name).'">'.$link->link_name.'</td><td class="hidden-xs">'.date('M j, Y', strtotime($link->expire)).'</td><td>'.$numFiles.'</td><td class="hidden-xs"><a href="/links/details/'.$link->link_id.'/'.str_replace(' ', '-', $link->link_name).'" title="Edit Link" data-tooltip="tooltip"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;<a href="#edit-modal"  class="delete-link" title="Delete Link" data-tooltip="tooltip" data-toggle="modal" data-link="'.$link->link_id.'"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';
            }
        }
        
        $data = [
            'linkList' => $linkList,
            'header' => 'Expired File Links'
        ];
        
        $this->template('techUser');
        $this->view('admin.fileLinks', $data);
        $this->render();
    }
    
    //  View all currently active links
    public function activeLinks()
    {
        $model = $this->model('fileLinks');
        
        $links = $model->getLinks();
        $today = date('Y-m-d');
        $linkList = '';
        
        //  Cycle through the links and pull out expired ones
        foreach($links as $link)
        {
            if($link->expire >= $today)
            {
                $numFiles = $model->countLinkFiles($link->link_id);
                
                $linkList .= '<tr><td>'.Template::getUserName($link->user_id).'</td><td><a href="/links/details/'.$link->link_id.'/'.str_replace(' ', '-', $link->link_name).'">'.$link->link_name.'</td><td class="hidden-xs">'.date('M j, Y', strtotime($link->expire)).'</td><td>'.$numFiles.'</td><td class="hidden-xs"><a href="/links/details/'.$link->link_id.'/'.str_replace(' ', '-', $link->link_name).'" title="Edit Link" data-tooltip="tooltip"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;<a href="#edit-modal"  class="delete-link" title="Delete Link" data-tooltip="tooltip" data-toggle="modal" data-link="'.$link->link_id.'"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';
            }
        }
        
        $data = [
            'linkList' => $linkList,
            'header' => 'Active File Links'
        ];
        
        $this->template('techUser');
        $this->view('admin.fileLinks', $data);
        $this->render();
    }
    
/************************************************************************
*                      Customers Administration                         *
*************************************************************************/
    
    //  search form to find a customer to delete
    public function deleteCustomer()
    {
        $model = $this->model('systems');
        
        $data['header'] = 'Delete Customer';
        $data['link'] = 'delete-customer-confirm';
        
        $this->template('techUser');
        $this->view('admin.searchCustomer', $data);
        $this->render();
    }
    
    //  Ajax call to search the customer database based on the information inputed into the search form
    public function customerSearchForm($link)
    {
        $model = $this->model('customers');
        
        $custData = $model->searchCustomer($_POST['customer']);
        
        $custList = '';
        
        if(!$custData)
        {
            $custList = '<tr><td colspan="4" class="text-center"><h3>No Results</h3></td></tr>';
        }
        else
        {
            foreach($custData as $cust)
            {
                
                $custList .= '<tr><td><a href="/admin/'.$link.'/'.$cust->cust_id.'/'.str_replace(' ', '-', $cust->name).'">'.$cust->name.'</a></td><td>'.$cust->city.', '.$cust->state.'</td>';
            }
        }
        
        $this->template('techUser');
        $this->render($custList);
    }
    
    //  Ask to confirm that the customer should be deleted
    public function deleteCustomerConfirm($custID)
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
            ];
            
            $this->view('admin.confirmCustomerDelete', $data);
        }

        $this->template('techUser');
        $this->render();
    }
    
    //  Delete an existing customer
    public function deleteCustomerConfirmYes($custID)
    {
        $model = $this->model('customers');
        $fileModel = $this->model('files');
        $sysModel = $this->model('systems');
        
        $custName = $model->getCustData($custID)->name;
        
        //  Delete all files associated with the customer
        $files = $model->getAllFiles($custID);
        $filePath = Config::getFile('uploadRoot').Config::getFile('custPath').$custID;
        foreach($files as $file)
        {
            if($fileModel->deleteFile($file->file_id))
            {
                $model->deleteFile($file->file_id);
            }
        }
        $fileModel->deleteFolder($filePath);
        
        //  Delete all systems associated with the customer
        $systems = $model->getCustSystem($custID);
        foreach($systems as $sys)
        {
            $sysID = $sysModel->getSysID($sys->name);
            $sysModel->delSysType($custID, $sysID);
        }
        
        //  Remove the customer
        $model->deleteCustomer($custID);
        
        //  Note change in log files
        $msg = 'Customer ('.$custID.')'.$custName.' deleted by administrator ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('Customer-Change', $msg);
        
        $this->render('success');
    }
    
    //  Search form to change a customers ID number
    public function changeCustomerId()
    {
        $model = $this->model('systems');
        
        $data['header'] = 'Change Customer ID';
        $data['link'] = 'change-id-form';
        
        $this->template('techUser');
        $this->view('admin.searchCustomer', $data);
        $this->render();
    }
    
    //  Change customer ID form
    public function changeIDForm($custID)
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
            ];
            
            $this->view('admin.changeCustIDForm', $data);
        }

        $this->template('techUser');
        
        $this->render();
    }
    
    //  Submit the customer change form
    public function changeIDFormSubmit($custID)
    {
        $model = $this->model('customers');
        
        if($custData = $model->getCustData($_POST['newid']))
        {
            $content = 'duplicate';
        }
        else
        {
            $model->updateCustID($_POST['newid'], $custID);
            $content = 'success';
            
            //  Note change in log files
            $msg = 'Customer ('.$custID.')'.$custName.' ID changed to '.$_POST['newid'].' by administrator ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
            Logs::writeLog('Customer-Change', $msg);
        }
        
        $this->render($content);
    }
    
    //  Deactivate an existing customer - search customers
    public function deactivateCustomer()
    {
        $data = [
            'header' => 'Deactivate Customer',
            'link' => 'deactivate-customer-form'
        ];
        
        $this->template('techUser');
        $this->view('admin.searchCustomer', $data);
        $this->render();
    }
    
    //  Deactivate customer form
    public function deactivateCustomerForm($custID)
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
            ];
            
            $this->view('admin.confirmDeactivateCustomer', $data);
        }

        $this->template('techUser');
        $this->render();
    }
    
    //  Confirm to deactivate the customer
    public function deactivateCustomerConfirmYes($custID)
    {
        $model = $this->model('customers');
        
        $model->deactivateCustomer($custID);
        
        $this->render('success');
    }
    
    //  Reactive customer - search deactivated customers
    public function reactivateCustomer()
    {
        $model = $this->model('customers');
        $deactivated = $model->searchDeactivated();
        
        $data['custList'] = '';
        foreach($deactivated as $cust)
        {
            $data['custList'] .= '<tr><td><a href="#edit-modal" data-customer="'.$cust->cust_id.'" class="reactivate-link" data-toggle="modal">'.$cust->name.'</a></td><td>'.$cust->city.', '.$cust->state.'</td></tr>';
        }
        
        if(empty($data['custList']))
        {
            $data['custList'] = '<tr><td colspan="2">No Deactivated Users</td></tr>';
        }
        
        $this->template('techUser');
        $this->view('admin.listDeactivatedCustomers', $data);
        $this->render();
    }
    
    //  Confirm reactivating a customer
    public function reactivateCustomerConfirm($custID)
    {
        $model = $this->model('customers');
        
        $model->reactivateCustomer($custID);
        
        $this->render('success');
    }
}
