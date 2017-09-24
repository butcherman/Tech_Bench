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
            if(empty(self::getCore('baseURL')) && !preg_match('/^setup/', $_GET['url']))
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
//        return self::$conf['email'][$var];
        return self::getSetting($var);
    }
    
    //  Return a file information value from the config file
    public static function getFile($var)
    {
        return self::$conf['upload_paths'][$var];
    }
    
    //  Return the encryption key
    public static function getKey()
    {
        return self::$conf['encryption']['customerKey'];
    }
    
    //  Return a setting that is in the database
    public static function getSetting($setting)
    {
        $qry = 'SELECT `value` FROM `_settings` WHERE `setting` = :setting';
        $result = Database::getDB()->prepare($qry);
        $result->execute(['setting' => $setting]);
        
        $res = $result->fetch();
        
        return $res->value;
    }
    
    //  Update a setting that is in the database
    public static function updateSetting($setting, $value)
    {
        $qry = 'UPDATE `_settings` SET `value` = :value WHERE `setting` = :setting';
        Database::getDB()->prepare($qry)->execute(['value' => $value, 'setting' => $setting]);
    }
}
