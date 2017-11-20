<?php
/*
|   Reports controller will allow administrators to run reports on different subjects in the application
*/

class Reports extends Controller
{
    public function __construct()
    {
        Security::setPageLevel('report');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
    }
    
    //  Landing page shows the reports menu
    public function index()
    {
        $this->view('reports/index');
        $this->template('techUser');
        $this->render();
    }

/*************************************************************************************************
*                                          User Reports                                          *
**************************************************************************************************/
    
    //  Show the login activity of all registered users
    public function loginActivity()
    {
        $model = $this->model('users');
        
        $userList = $model->getUserList();
        
        $table = '';
        foreach($userList as $user)
        {
            $lastLogin = $model->lastLoginDate($user->user_id) ? date('m-d-Y g:i a', strtotime($model->lastLoginDate($user->user_id))) : 'Never';
            $table .= '<tr><td><a href="/reports/user-stats/'.$user->user_id.'">'.$user->first_name.' '.$user->last_name.'</a></td>
                        <td>'.$lastLogin.'</td>
                        <td>'.$model->loginData($user->user_id, '7').'</td>
                        <td>'.$model->loginData($user->user_id, '30').'</td>
                        <td>'.$model->loginData($user->user_id, '90').'</td></tr>';
        }
        
        $data['loginTable'] = $table;
        $this->view('reports/user_login_activity', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Show statistics for a specific user
    public function userStats($userID = '')
    {
        $model = $this->model('reportModel');
        
        //  Build and array that contains the names and numbers of the last six months
        $first  = strtotime('first day next month');
        $months = [];
        $monthNum = [];
        for ($i = 6; $i >= 1; $i--) {
            array_push($months, date('M', strtotime("-$i month", $first)));
            array_push($monthNum, date('n', strtotime("-$i month", $first)));
        }
        
        if(empty($userID))
        {
            $userModel = $this->model('users');
            $userList = $userModel->getUserList();

            $optList = '<option></option>';
            foreach($userList as $user)
            {
                $optList .= '<option value="'.$user->user_id.'">'.$user->first_name.' '.$user->last_name.'</option>';
            }

            $data['optList'] = $optList;

            $this->view('reports/user_stats_list', $data);
        }
        else
        {
            //  Determine how many times the user has logged in each month for the last six months
            $loginPerMonth = [];
            $filesPerMonth = [];
            foreach($monthNum as $key => $num)
            {
                $loginsPerMonth[$key] = $model->userLoginsPerMonth($userID, $num);
            }
            
            $data = [
                'user' => Template::getUserName($userID),
                'sysFiles'      => $model->countSysFiles($userID),
                'sysFiles30'    => $model->countSysFiles($userID, true),
                'custFiles'     => $model->countCustBackups($userID),
                'custFiles30'   => $model->countCustBackups($userID, true),
                'custNonBak'    => $model->countCustFiles($userID),
                'custNonBak30'  => $model->countCustFiles($userID, true),
                'custNotes'     => $model->countCustNotes($userID),
                'custNotes30'   => $model->countCustNotes($userID, true),
                'techTips'      => $model->countTechTips($userID),
                'techTips30'    => $model->countTechTips($userID, true),
                'tipComments'   => $model->countTipComments($userID),
                'tipComments30' => $model->countTipComments($userID, true),
                'months'        => '"'.implode('", "', $months).'"',
                'loginData'     => '"'.implode('", "', $loginsPerMonth).'"'
            ];
            
            $this->view('reports/user_stats_view', $data);
        }
        
        $this->template('techUser');
        $this->render();
    }
    
/*************************************************************************************************
*                                        Customer Reports                                        *
**************************************************************************************************/
    
    //  Landing page to determine which customers have a backup
    public function customerBackups()
    {
        $this->view('reports/customer_backups');
        $this->template('techUser');
        $this->render();
    }
    
    //  Submit search form to see which customers have a backup
    public function searchCustBackups()
    {
        $model = $this->model('customers');
        
        $data = '';
        $custList = $model->searchCustomer($_POST['customer']);
        
        foreach($custList as $cust)
        {
            $backup = $model->hasBackup($cust->cust_id) ? 'go.png' : 'stop.png';
            
            $data .= '<tr><td>'.$cust->name.'</td><td><img src="/source/img/'.$backup.'" /></td></tr>';
        }
        
        $this->render($data);
    }
    
    //  Landing page to see which customers have a system assigned
    public function customerSystems()
    {
        $this->view('reports/customer_systems');
        $this->template('techUser');
        $this->render();
    }
    
    //  Submit search form to see which customers have a system assigned
    public function searchCustSystems()
    {
        $model = $this->model('customers');
        
        $data = '';
        $custList = $model->searchCustomer($_POST['customer']);
        
        foreach($custList as $cust)
        {
            $backup = $model->hasSystem($cust->cust_id) > 0 ? 'go.png' : 'stop.png';
            
            $data .= '<tr><td>'.$cust->name.'</td><td><img src="/source/img/'.$backup.'" /></td></tr>';
        }
        
        $this->render($data);
    }
    
/*************************************************************************************************
*                                          Files Reports                                         *
**************************************************************************************************/
    
    //  Check how many valid files are in the system, and how much of th ehard drive is geing used
    public function systemFiles()
    {
        $model = $this->model('reportModel');
        
        //  Determine disk space data
        $space = disk_total_space('/');
        $free = disk_free_space('/');
        $used = number_format((($space - $free) / $space) * 100, 2).'%';
        
        //  Determine file count information
        $countCust = $model->compareFiles(Config::getFile('custPath'), 'customer_files');
        $countSyst = $model->compareFiles(Config::getFile('sysPath'), 'system_files');
        $countTips = $model->compareFiles(Config::getFile('tipPath'), 'tech_tip_files');
        $countUser = $model->compareFiles(Config::getFile('userPath'), 'user_files');
        $countComp = $model->compareFiles(Config::getFile('formPath'), 'company_files');
        

        $data = [
            'numFiles'    => $model->countFiles(),
            'totalSpace'  => $space,
            'freeSpace'   => $free,
            'percent'     => $used,
            'custValid'   => $countCust['valid'],
            'custMissing' => $countCust['missing'],
            'custUnknown' => $countCust['unknown'],
            'sysValid'    => $countSyst['valid'],
            'sysMissing'  => $countSyst['missing'],
            'sysUnknown'  => $countSyst['unknown'],
            'tipValid'    => $countTips['valid'],
            'tipMissing'  => $countTips['missing'],
            'tipUnknown'  => $countTips['unknown'],
            'usrValid'    => $countUser['valid'],
            'usrMissing'  => $countUser['missing'],
            'usrUnknown'  => $countUser['unknown'],
            'compValid'   => $countComp['valid'],
            'compMissing' => $countComp['missing'],
            'compUnknown' => $countComp['unknown']
        ];
        
        $this->view('reports/system_files', $data);
        $this->template('techUser');
        $this->render();
    }
}
