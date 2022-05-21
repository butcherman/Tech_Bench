<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GetModulesOnlineController extends Controller
{
    /**
     * Get the latest modules available from the Tech Bench Modules Github repository
     */
    // public function __invoke(Request $request)
    // {
    //     // $response = Http::get('https://raw.githubusercontent.com/butcherman/Tech_Bench_Modules/main/module_list.json');
    //     // return $response;

    //     return [
    //         [
    //             "module_name"     => "File Link Module",
    //             "alias"           => "filelinkmodule",
    //             "module_version"  => 1.0,
    //             "min_tb_version"  => 6.0,
    //             "max_tb_version"  => null,
    //             "download_link"   => "https://github.com/butcherman/FileLinkModule/archive/refs/tags/1.0.0.zip",
    //             "description"     => "This module is an extension to the Tech Bench application. It allows users to create file links that will allow guests to either upload files for the user to access, or download files that the user has loaded to the link. Each link has a unique URL to access it, as well as a set expiration date. Once that date has passed, the files in the link are no longer accessible by guest access.  \n By using this feature, users can securely pass files to customers that may be too large for emailing or other means."
    //         ],
    //         [
    //             "module_name"     => "Some Other Module",
    //             "alias"           => "someothermodule",
    //             "module_version"  => 1.0,
    //             "min_tb_version"  => 6.0,
    //             "max_tb_version"  => null,
    //             "download_link"   => "#",
    //             "description"     => "This module is completely made up adn does not exist"
    //         ]
    //     ];
    // }
}
