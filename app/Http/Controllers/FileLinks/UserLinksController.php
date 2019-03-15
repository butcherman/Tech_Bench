<?php

namespace App\Http\Controllers\FileLinks;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//use Zip;
use App\Files;
use App\FileLinks;
//use App\Customers;
//use App\CustomerFiles;
use App\FileLinkFiles;
//use App\FileLinkNotes;
//use Illuminate\Http\Request;
//use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Storage;

class UserLinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
        echo 'index';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    //  Ajax call to show the links for a specific user
    public function show($id)
    {
        $links = FileLinks::where('user_id', $id)
            ->withCount('FileLinkFiles')
            ->orderBy('expire', 'desc')
            ->get();
                
        //  Reformat the expire field to be a readable date
        foreach($links as $link)
        {
            $link->url = route('links.details', [$link->link_id, urlencode($link->link_name)]);
            $link->showClass  = $link->expire < date('Y-m-d') ? 'table-danger' : '';
            $link->expire = date('M d, Y', strtotime($link->expire));
        }
        
        return response()->json($links);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
