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
    
    //  Ajax call to submit the user settings
    public function submitUserSettings()
    {
        $model = $this->model('users');
        
        //  Update primary user information
        $userData = [
            'username' => $_POST['username'],
            'first_name' => $_POST['firstName'],
            'last_name' => $_POST['lastName'],
            'email' => $_POST['email']
        ];
        $model->updateUserData($_SESSION['id'], $userData);
        
        //  Update notification settings
        $userSettings = [
            'tech_tip' => isset($_POST['emailTechTip']) ? 1 : 0,
            'file_link' => isset($_POST['emailFileLink']) ? 1 : 0,
            'notificaton' => isset($_POST['emailNotifications']) ? 1 : 0
        ];
        $model->updateUserSettings($_SESSION['id'], $userSettings);
        
        $this->render('success');
    }
    
    //  Change password page
    public function password()
    {
        $data['change'] = $_SESSION['changePassword'] ? 'You Must Change Your Password' : '';
        
        $this->view('tech.changePassword', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to confirm that the current password is valid
    public function checkPassword()
    {
        $model = $this->model('users');
        $valid = $model->checkPassword($_SESSION['id'], $_POST['current']);
        
        $this->render(json_encode($valid));
    }
    
    //  Ajax call to submit the users new password
    public function updatePassword()
    {
        $model = $this->model('users');
        
        $model->setPassword($_SESSION['id'], $_POST['newpass']);
        $model->removeForcePasswordChange($_SESSION['id']);
        $_SESSION['changePassword'] = 0;
        
        $this->render('success');
    }
}
