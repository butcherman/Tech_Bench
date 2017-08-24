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
            
//            echo 'User '.$user->user_id.'<pre>';
//            print_r($logins);
//            echo '</pre>';
        }
        
        $data['loginTable'] = $table;
        $this->view('reports.loginActivity', $data);
        $this->template('techUser');
        $this->render();
    }
}