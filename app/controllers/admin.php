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
        }
        
        $this->render('success');
    }
}