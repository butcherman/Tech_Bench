<?php
/*
|   Logout Controller logs out user and destroys any "Remember Me" cookie that may exist
*/
class Logout extends Controller
{
    public function index()
    {
        //  Destroy session information and remember me cookie
        $_SESSION = [];
        session_destroy();
        setcookie(str_replace(' ', '', Config::getCore('title')), '', time()-3600*24*365, '/');
        
        //  Route user back to login page
        header('Location: /');
        die();
    }
}
