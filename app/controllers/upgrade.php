<?php
/*
|   Upgrade Controller is for upgrading the database to the newer version while maintaining all 
|   database data.
*/
class Upgrade extends Controller
{
    public function __construct()
    {
        Security::setPageLevel('site admin');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
    }
    
    public function index()
    {
        $this->view('admin.site.upgrade');
        $this->template('standard');
        $this->render();
    }
}