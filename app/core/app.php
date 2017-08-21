<?php
/*
|   App class will clean the url to create the Controller, Method, and any necessary vairiable to be passed.
|   The application will then run and call the necessary Controller and Method
*/
class App
{
    protected $controller, $method = 'index', $params = [];
    
    public function __construct()
    {
        //  Break URL into array
        $url = $this->parseURL();
        
        //  Assign the first paramater of the array (if set) as the controller
        if(empty($url[0]))
        {
            $this->controller = 'home';
        }
        else if(file_exists(__DIR__.'/../controllers/'.strtolower(str_replace('-', '', $url[0])).'.php'))
        {
            $this->controller = strtolower(str_replace('-', '', $url[0]));
            unset($url[0]);
        }
        else
        {
            //  If an invalid controller was passed, route to a custom error page
            Logs::writeLog('404', 'Unknown Controller '.$_GET['url']);
            header('Location: /err/_404');
            die();
        }
        
        //  Open the controller file and create the Controller Object
        require_once __DIR__.'/../controllers/'.$this->controller.'.php';
        $this->controller = new $this->controller;
        
        //  Check to see if the url array has a valid method for the controller class
        if(isset($url[1]))
        {
            if(method_exists($this->controller, str_replace('-', '', $url[1])))
            {
                $this->method = str_replace('-', '', $url[1]);
                unset($url[1]);
            }
        }
        
        //  Take any remaining values in the url array and asign to paramaters
        $this->params = $url ? array_values($url) : array();
        
        //  Call the method for the class and pass in any paramaters
        call_user_func_array(array($this->controller, $this->method), $this->params);
    }
    
    //  Function will take the URL and break it down into an array to be processed
    private function parseURL()
    {
        if(isset($_GET['url']))
        {
            return explode('/', filter_var(rtrim($_GET['url'], '/')));
        }
    }
}
