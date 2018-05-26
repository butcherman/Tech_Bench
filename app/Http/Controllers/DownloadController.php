<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadController extends Controller
{
    //  Class to download the file
    public function index($fileID, $fileName)
    {
        echo 'download page';
    }
}
