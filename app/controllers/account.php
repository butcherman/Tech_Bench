<?php
/*
|   Account settings page for logged in users
*/
class Account extends Controller
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
    }
    
    //  Account home page allows users to adjust their settings
    public function index()
    {
        $model = $this->model('users');
        $userData = $model->getUserData($_SESSION['id']);
        $userSettings = $model->getUserSettings($_SESSION['id']);
        
        
        
        
        
        $data = [
            'username' => $userData->username,
            'first_name' => $userData->first_name,
            'last_name' => $userData->last_name,
            'email' => $userData->email,
            'em_tech_tip' => $userSettings->em_tech_tip ? ' checked' : '',
            'em_file_link' => $userSettings->em_file_link ? ' checked' : '',
            'em_sys_notification' => $userSettings->em_sys_notification ? ' checked' : '',
            'auto_delete_link' => $userSettings->auto_delete_link ? ' checked' : ''
        ];
        
        
        $this->view('tech.account', $data);
        $this->template('techUser');
        $this->render();
    }
}
