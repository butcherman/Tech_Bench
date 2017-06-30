<?php
class Err // extends Controller
{
    public function __construct(){}
    
    public function index()
    {
        echo 'oops<br><pre>';
        print_r($_SESSION);
        echo '</pre>';
    }
}
