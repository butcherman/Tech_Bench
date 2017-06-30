<?php
/*
|   Config Class consists of static functions that will pull the config file and return the necessary paramaters.
|   Config file is called "config.ini" and can be found in /config/ folder
*/

class Config
{
    private static $conf = NULL;
    
    public static function init()
    {
        //  Determine the location of the configuration file
        $configLocation = __DIR__.'/../../config/config.ini';
        
        //  Check if the config file exists, if it does, assign it to the $conf variable
        if(file_exists($configLocation))
        {
            Self::$conf = parse_ini_file($configLocation, 1);
            if(empty(self::getCore('baseURL')) && $_GET['url'] != 'setup')
            {
                header('Location: /setup');
                die();
            }
        }
    }
    
    //  Return a core value from the config file
    public static function getCore($var)
    {
        return self::$conf['core'][$var];
    }
    
    //  Return a database value from the config file
    public static function getDB($var)
    {
        return self::$conf['database'][$var];
    }
    
    //  Return an email value from the config file
    public static function getEmail($var)
    {
        return self::$conf['email'][$var];
    }
    
    //  Return a file information value from the config file
    public static function getFile($var)
    {
        return self::$conf['upload_paths'][$var];
    }
}
