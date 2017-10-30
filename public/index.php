<?php
/*
|
|   Tech Bench is a custom Content Management System (CMS) built to aid service technicians store and 
|   share information for the systems and customers they maintain.
|
|   Author  - Ron Butcher
|   Version - 3.0.0
|
|   Index.php file is the initial file that will begin the application
|   All necessary static classes will be called
|
*/

session_start();

//  Define version information as Global Variables
define('VERSION', '3.0.1');
define('RELEASE', '10-29-2017');
define('DBVERSION', '3.0');

//  Call necessary required files
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../app/core/app.php';
require_once __DIR__.'/../app/core/logs.php';
require_once __DIR__.'/../app/core/config.php';
require_once __DIR__.'/../app/core/controller.php';
require_once __DIR__.'/../app/core/database.php';
require_once __DIR__.'/../app/core/security.php';
require_once __DIR__.'/../app/core/email.php';
require_once __DIR__.'/../app/core/template.php';
require_once __DIR__.'/../app/core/vcardDownloader.php';

//  Initialize the necessary static classes
Config::init();
Database::init();

date_default_timezone_set('America/Los_Angeles');

//echo '<pre>';
//print_r($_SESSION);
//die();

//  Begin the application
$app = new App;
