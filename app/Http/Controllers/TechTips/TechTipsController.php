<?php

namespace App\Http\Controllers\TechTips;

use App\Domains\Equipment\GetEquipmentData;
use App\Domains\TechTips\GetTechTips;
use App\User;
use App\Files;
use App\TechTips;
use App\TechTipFavs;
use App\TechTipFiles;
use App\TechTipTypes;
use App\TechTipSystems;
use Illuminate\Http\File;
use App\SystemCategories;
use Illuminate\Http\Request;
use App\Notifications\NewTechTip;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\TechTipsCollection;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\TechTipTypesCollection;
use App\Http\Resources\SystemCategoriesCollection;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use App\Http\Resources\SystemCategoriesCollection as CategoriesCollection;


use App\Domains\TechTips\GetTechTipTypes;
use App\Domains\TechTips\SetTechTips;
use App\Domains\Users\GetUserStats;
use App\Domains\Users\UserFavs;
use App\Http\Requests\TechTipEditTipRequest;
use App\Http\Requests\TechTipNewTipRequest;
use App\Http\Requests\TechTipProcessImageRequest;
use App\Http\Requests\TechTipSearchRequest;

class TechTipsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Tech Tips landing page
    public function index()
    {
        return view('tips.index', [
            'tipTypes' => (new GetTechTipTypes)->execute(true),
            'sysTypes' => (new GetEquipmentData)->getAllEquipmentWithDataList(),
        ]);
    }

    //  Search for an existing tip - If no paramaters, return all tips
    public function search(TechTipSearchRequest $request)
    {
        return (new GetTechTips)->searchTips($request);
    }

    //  Process an image that is attached to a tech tip
    public function processImage(TechTipProcessImageRequest $request)
    {
        $this->authorize('hasAccess', 'Create Tech Tip');

        $imgLocation = (new SetTechTips)->processTipImage($request);
        return response()->json(['location' => $imgLocation]);

    }

    //  Create a new Tech Tip form
    public function create()
    {
        $this->authorize('hasAccess', 'Create Tech Tip');

        return view('tips.create', [
            'tipTypes' => (new GetTechTipTypes)->execute(true),
            'sysTypes' => (new GetEquipmentData)->getAllEquipmentWithDataList(),
        ]);
    }

    //  Submit the form to create a new tech tip
    public function store(TechTipNewTipRequest $request)
    {
        $this->authorize('hasAccess', 'Create Tech Tip');

        $tipData = (new SetTechTips)->processNewTip($request);
        return response()->json(['success' => $tipData]);
    }

    //  Details controller - will move to the show controller with just the tech tip id
    public function details($id)
    {
        $tipData = (new GetTechTips)->getTipDetails($id);

        return view('tips.details', [
            'details' => $tipData->toJson(),
            'isFav'   => (new GetUserStats)->checkForTechTipFav($id) ? 'true' : 'false',   // empty($isFav) ? 'false' : 'true',
        ]);

    }

    //  Show the details about the tech tip
    public function show($id)
    {
        $tipData = (new GetTechTips)->getTipDetails($id);

        return view('tips.details', [
            'details' => $tipData['data'],
            'isFav'   => (new GetUserStats)->checkForTechTipFav($id) ? 'true' : 'false',   // empty($isFav) ? 'false' : 'true',
            'files'   => $tipData['files'],
        ]);
    }

    //  Add or remove this tip as a favorite of the user
    public function toggleFav($action, $id)
    {
        (new UserFavs)->updateTechTipFav($id);
        return response()->json(['success' => true]);
    }

    //  Edit an existing tech tip
    public function edit($id)
    {
        $this->authorize('hasAccess', 'Edit Tech Tip');

        $tipData = (new GetTechTips)->getTipDetails($id);
        Log::emergency('tip details', array($tipData));
        if(!$tipData['details'])
        {
            return view('tips.tipNotFound');
        }

        return view('tips.editTip', [
            'tipTypes' => (new GetTechTipTypes)->execute(true),
            'sysTypes' => (new GetEquipmentData)->getAllEquipmentWithDataList(),
            'tipData'  => $tipData,
        ]);
    }

    //  Store the edited Tech Tip
    public function update(TechTipEditTipRequest $request, $id)
    {
        $this->authorize('hasAccess', 'Edit Tech Tip');

        (new SetTechTips)->processEditTip($request, $id);

        return response()->json(['success' => true]);
    }








    //  Soft delet the Tech Tip
    public function destroy($id)
    {
        // Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        $this->authorize('hasAccess', 'Delete Tech Tip');

        // //  Remove the Tip from any users favorites
        // TechTipFavs::where('tip_id', $id)->delete();

        // //  Disable the tip
        // TechTips::find($id)->delete();
        // Log::warning('User - '.Auth::user()->user_id.' deleted Tech Tip ID - '.$id);

        (new SetTechTips)->deleteTip($id);
        return response()->json(['success' => true]);
    }
}
