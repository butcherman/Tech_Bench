<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\TechTips\NewTipRequest;
use App\Http\Requests\TechTips\EditTipRequest;
use App\Http\Requests\TechTips\SearchTipsRequest;
use App\Http\Requests\TechTips\UploadImageRequest;

use App\Domains\User\GetUserStats;
use App\Domains\TechTips\SearchTips;
use App\Domains\TechTips\SetTechTips;
use App\Domains\TechTips\GetTechTips;
use App\Domains\User\SetUserFavorites;
use App\Domains\Equipment\GetEquipment;
use App\Domains\TechTips\GetTechTipTypes;

use PDF;

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
        return view('tips.create', [
            'types' => (new GetTechTipTypes)->execute(),
            'equip' => (new GetEquipment)->getAllEquipment(true),
        ]);
    }

    //  Upload an image file to be included with the tech tip text
    public function uploadImage(UploadImageRequest $request)
    {
        $local = (new SetTechTips)->processImage($request);
        return response()->json(['location' => $local]);
    }

    //  Store a new Tech Tip into the database along with any files attached
    public function store(NewTipRequest $request)
    {
        $tip = (new SetTechTips)->processNewTip($request, Auth::user()->user_id);
        return response()->json(['success' => true, 'tip_id' => $tip]);
    }

    //  Show the Tech Tip Details
    public function show($id)
    {
        $details = (new GetTechTips)->getTip($id);

        if(!$details)
        {
            abort(404, 'The Tech Tip you are looking for does not exist or cannot be found');
        }

        $isFav = (new GetUserStats(Auth::user()->user_id))->checkTipForFav($id);
        return view('tips.details', [
            'details' => $details,
            'isFav' => $isFav ? true : false,
        ]);
    }

    //  Toggle if a Tech Tip is listed as a users favorite or not
    public function toggleFav($id)
    {
        $result = (new SetUserFavorites)->toggleTechTipFavorite($id, Auth::user()->user_id);
        return response()->json(['success' => true, 'favorite' => $result]);
    }

    //  Download a tip as a pdf file
    public function download($id)
    {
        $tipData = (new GetTechTips)->getTip($id);

        $pdf = PDF::loadView('pdf.techTip', [
            'data' => $tipData,
        ]);

        return $pdf->download($tipData->subject.'.pdf');
    }

    //  Edit an existing Tech Tip
    public function edit($id)
    {
        $details = (new GetTechTips)->getTip($id);

        if(!$details)
        {
            abort(404, 'The Tech Tip you are looking for does not exist or cannot be found');
        }

        return view('tips.edit', [
            'types'   => (new GetTechTipTypes)->execute(),
            'equip'   => (new GetEquipment)->getAllEquipment(true),
            'details' => $details,
        ]);
    }

    //  Submit the edit tip form
    public function update(EditTipRequest $request, $id)
    {
        (new SetTechTips)->processEditTip($request, $id, Auth::user()->user_Id);
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        (new SetTechTips)->deactivateTip($id);
        Log::notice('User '.Auth::user()->full_name.' has deleted Tech Tip ID '.$id);
        return response()->json(['success' => true]);
    }
}
