<?php
/*
|   Maintenance operations to keep the application running smoothly
*/

class Maintenance extends Controller
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
    
    //  Maintenance landing page
    public function index()
    {
        $this->view('maintenance.home');
        $this->template('techUser');
        $this->render();
    }
    
    //  Show maintenance mode enable/disable page
    public function maintenanceMode()
    {
        $data = [
            'maintMode' => Template::inMaintenanceMode() ? 'checked' : ''
        ];
        
        $this->view('maintenance.maintenanceMode', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to toggle maintenance mode
    public function toggleMaintMode()
    {
        if(Template::inMaintenanceMode())
        {
            Template::toggleMaintMode(0);
        }
        else
        {
            Template::toggleMaintMode(1);
        }
    }
}