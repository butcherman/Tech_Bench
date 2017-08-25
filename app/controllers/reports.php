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
        $this->view('reports.home');
        $this->template('techUser');
        $this->render();
    }
    
    //  Show the login activity of all registered users
    public function loginActivity()
    {
        $model = $this->model('users');
        
        $userList = $model->getUserList();
        
        $table = '';
        foreach($userList as $user)
        {
            $lastLogin = $model->lastLoginDate($user->user_id) ? date('m-d-Y g:i a', strtotime($model->lastLoginDate($user->user_id))) : 'Never';
            $table .= '<tr><td>'.$user->first_name.' '.$user->last_name.'</td>
                        <td>'.$lastLogin.'</td>
                        <td>'.$model->loginData($user->user_id, '7').'</td>
                        <td>'.$model->loginData($user->user_id, '30').'</td>
                        <td>'.$model->loginData($user->user_id, '90').'</td></tr>';
        }
        
        $data['loginTable'] = $table;
        $this->view('reports.loginActivity', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Show statistics for a specific user
    public function userStats($userID = '')
    {
        $model = $this->model('reportModel');
        
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

            $this->view('reports.userStatsList', $data);
        }
        else
        {
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
                'tipComments30' => $model->countTipComments($userID, true)
            ];
            
            $this->view('reports.userStatsView', $data);
        }
        
        $this->template('techUser');
        $this->render();
    }
}