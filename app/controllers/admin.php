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
    
    //  Page to reset a users password for them
    public function resetPassword()
    {
        $this->view('admin.resetPassword');
        $this->template('techUser');
        $this->render();
    }
}