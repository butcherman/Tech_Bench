<?php
class Err extends Controller
{
    public function __construct()
    {
        ob_start();
            echo $_GET['url'].'\n';
            print_r($_SESSION);
        $msg = ob_get_clean();
        
        Logs::writeLog('Err-Page', $msg);
    }
    
    //  Generic error page 
    public function index()
    {
        $this->view('error.generic');
        $this->render();
    }
    
    //  Maintenance Mode Page
    public function maintenance()
    {
        $this->template('errorPage');
        $this->view('error.maintenance');
        $this->render();
    }
    
    //  User trying to access a restricted page
    public function restricted()
    {
        $this->template('errorPage');
        $this->view('error.restricted');
        $this->render();
    }
    
    //  404 - page not found error
    public function _404()
    {
        $this->template('errorPage');
        $this->view('error.404');
        $this->render();
    }
    
    //  Invalid system type
    public function invalidSystem()
    {
        $this->template('errorPage');
        $this->view('error.invalidSystem');
        $this->render();
    }
    
    //  User has tried to log in too many times
    public function failedLogin()
    {
        $this->template('errorPage');
        $this->view('error.failedLogin');
        $this->render();
    }
}
