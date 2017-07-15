<?php
/*
|   Dashboard controller is the initial landing page for registered users
*/
class Dashboard extends Controller
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
        $this->template('techUser');
    }
    
    public function index()
    {
        $this->view('tech.dashboard');
        $this->render();
    }
}
