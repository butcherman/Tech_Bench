<?php
class Home extends Controller
{    
    public function __construct()
    {
        Security::setPageLevel('open');
        if(!Security::doIBelong(true))
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
    }
    
    //  index is the landing page that will allow the user to login
    public function index()
    {
        $model = $this->model('users');
        //  Check to see if the user is already logged in
        if(Security::isLoggedIn())
        {
            $model = $this->model('users');
            $userHome = $model->getHomeLocation($_SESSION['id']);
            header('Location: '.$userHome);
            die();
        }
        else if(isset($_COOKIE[str_replace(' ', '', Config::getCore('title'))]))
        {
            if($userID = $model->checkCookie())
            {
                $userData = $model->getUserData($userID);
                $this->logInUser($userData);
                $userHome = $model->getHomeLocation($userID);
                header('Location: '.$userHome);
                die();
            }
        }
        else if($model->countFailedLogin($this->getRealIpAddr()) > 5)
        {
            header('Location: /err/failed-login');
            die();
        }
        
        $this->template('standard');
        $this->view('home.login');
        $this->render();
    }
    
    //  Ajax call to check the users login information
    public function submitLogin()
    {
        $model = $this->model('users');
        $userHome = false;
        
        if($model->countFailedLogin($this->getRealIpAddr()) > 10)
        {
            header('Location: /err/failed-login');
            die();
        }
        
        //  Make sure that the username and password were filled out before submitting to the database and check for valid user ID
        if(!empty($_POST['username']) && !empty($_POST['password']) && $userID = $model->checkLoginData($_POST['username'], $_POST['password']))
        {
            $userData = $model->getUserData($userID);
            $this->logInUser($userData);
            //  If the user has checked the remember me box, set a remember me cookie
            if(isset($_POST['remember']) && $_POST['remember'] === 'remember-me')
            {
                $model->setCookie($userID);
            }
            $userHome = $model->getHomeLocation($userID);
        }
        else if(!$model->checkLoginData($_POST['username'], $_POST['password']))
        {
            $model->logFailedLogin($this->getRealIpAddr());
        }
        
        $this->render($userHome);
    }
    
    //  forgot-password link allows user to request a new link to reset their password
    public function forgotPassword()
    {
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
        
        //  Check to see if the user is already logged in
        if(Security::isLoggedIn())
        {
            $model = $this->model('users');
            $userHome = $model->getHomeLocation($_SESSION['id']);
            header('Location: '.$userHome);
            die();
        }
        else if(isset($_COOKIE[str_replace(' ', '', Config::getCore('title'))]))
        {
            $model = $this->model('users');
            if($userID = $model->checkCookie())
            {
                $userHome = $model->getHomeLocation($userID);
                header('Location: '.$userHome);
                die();
            }
        }
        
        $this->template('standard');
        $this->view('home.reset');
        $this->render();
    }
    
    //  Ajax call to submit forgotten password form and email new password link to user
    public function submitForgotPassword()
    {
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
        
        $model = $this->model('users');
        $valid = false;
        
        //  Check if the data the user entered is valid or not - note:  both username and email must match
        if($userID = $model->checkForgottenData($_POST['username'], $_POST['email']))
        {
            //  Create the password reset link
            $userData = $model->getUserData($userID);
            $passLink = $model->createResetLink($userID);
            $valid = 'success';
            
            //  Create the view for the email
            $data['resetLink'] = $passLink;
            $emBody = $this->emailView('user.resetPasswordLink', $data);
            
            //  Email the user with the reset link
            Email::init();
            Email::addSubject('Tech Bench - Reset Password Link');
            Email::addUser($userData->email);
            Email::addBody($emBody);
            $valid = Email::send();
        }

        $this->render($valid);
    }
    
    //  Function to allow the user to recover their password
    public function resetPassword($link)
    {
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
        
        $model = $this->model('users');
        
        if($userID = $model->checkResetLink($link))
        {
            $data['link'] = $link;
            $this->view('home.resetPassword', $data);
        }
        else
        {
            $this->view('error.badLink');
        }
        
        $this->template('standard');
        $this->render();
    }
    
    //  Function to reset the users password from the forgotten password form
    public function submitResetPassword()
    {
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
        
        $model = $this->model('users');
        $valid = false;
        
        if($userID = $model->checkResetLink($_POST['link']) && $_POST['newPass'] === $_POST['confPass'])
        {
            $model->setPassword($userID, $_POST['newPass']);
            $model->delResetLink($userID);
            Logs::writeLog('Users', 'Password Updated via Reset Link for User: '.$userID);
            $valid = 'success';
        }
        
        $this->render($valid);
    }
    
    //  Function that only shows yes or no dialog boxes
    public function yesOrNo()
    {
        $this->view('confirm.yesorno');
        $this->render();
    }
    
    //  Function to create the necessary session variables to log the user in
    private function logInUser($userData)
    {
        $model = $this->model('users');
        $model->noteuserLogin($userData->user_id);
        
        session_regenerate_id();
        $_SESSION['valid'] = 1;
        $_SESSION['id'] = $userData->user_id;
        $_SESSION['username'] = $userData->username;
        $_SESSION['name'] = $userData->first_name.' '.$userData->last_name;
        $_SESSION['changePassword'] = $userData->change_password;
    }
    
    //  Function to get the real IP Address of the user
    private function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
