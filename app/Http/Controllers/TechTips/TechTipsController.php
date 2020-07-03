<?php

namespace App\Http\Controllers\TechTips;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests\TechTips\SearchTipsRequest;

use App\Domains\TechTips\SearchTips;
use App\Domains\Equipment\GetEquipment;
use App\Domains\TechTips\GetTechTipTypes;

class TechTipsController extends Controller
{
    //  Landing page - tech tip search form
    public function index()
    {
        return view('tips.index', [
            'tipTypes'  => (new GetTechTipTypes)->execute(),
            'equipment' => (new GetEquipment)->getAllEquipment(true),
        ]);
    }

    //  Submit a search request
    public function search(SearchTipsRequest $request)
    {
        return (new SearchTips)->execute($request);
    }

    //  New tech tip form
    public function create()
    {
        //
        echo 'new tip form';
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

    //  Show the Tech Tip Details
    public function show($id)
    {
        //
        echo 'tip details';
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
