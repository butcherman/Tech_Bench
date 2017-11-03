<?php
/*
|   About controller shows app version information
*/
class About extends Controller
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
        $this->view('home/about');
        $this->render();
    }
}
