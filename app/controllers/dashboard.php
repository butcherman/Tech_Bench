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
        }
        $this->template('techUser');
    }
    
    public function index()
    {
        Logs::writeLog('test', 'this is a test');
        $this->view('tech.dashboard');
        $this->render();
    }
}
