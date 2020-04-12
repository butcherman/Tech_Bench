<?php

namespace App\Http\Controllers\FileLinks;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Domains\FileLinks\GetFileLinks;
use App\Domains\FileLinks\KillFileLink;
use App\Domains\FileLinks\CreateFileLink;
use App\Domains\FileLinks\SaveFileLinkFile;
use App\Domains\FileLinks\GetFileLinkDetails;
use App\Domains\FileLinks\SetFileLinkDetails;
use App\Domains\FileTypes\GetCustomerFileTypes;

use App\Http\Requests\NewFileLinkRequest;
use App\Http\Requests\UpdateFileLinkRequest;
use App\Http\Requests\UpdateFileLinkInstructionsRequest;

class FileLinksController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        //  Verify the user is logged in and has permissions for this page
        $this->middleware('auth');
        $this->middleware(function($request, $next) {
            $this->authorize('hasAccess', 'Use File Links');
            return $next($request);
        });
    }

    //  Landing page shows all links that the user owns
    public function index()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return view('links.index');
    }

    //  Ajax call to show the links for a specific user
    public function find($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        //  If the user is trying to access the links of another user, they must have the proper permissions permissions
        if ($id != 0)
        {
            $this->authorize('hasAccess', 'manage_users');
        }

        return (new GetFileLinks($id, true))->execute();
    }

    //  Create a new file link form
    public function create()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return view('links.newLink');
    }

    //  Submit the new file link form
    public function store(NewFileLinkRequest $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        //  If there are files, process them first in their chunks
        if(!empty($request->file))
        {
            (new SaveFileLinkFile)->execute($request);
            return response()->json(['success' => true]);
        }

        $linkObj = new CreateFileLink();
        $linkData = $linkObj->create($request);

        Log::info('File Link Created for '.Auth::user()->full_name.'.  Link Data - ', $linkData);
        return response()->json($linkData);
    }

    //  Show details about a file link
    public function details($id, /** @scrutinizer ignore-unused */ $name)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        $linkDataObj = new GetFileLinkDetails($id);

        //  If the link is invalid, return an error page
        if(!$linkDataObj->isLinkValid())
        {
            Log::warning('User '.Auth::user()->full_name.' tried to view invalid file link', ['user_id' => Auth::user()->user_id, 'link_id' => $id]);
            return view('links.badLink');
        }

        return view('links.details', [
            'link_id'    => $id,
            'cust_id'    => $linkDataObj->getLinkCustomer(),
            'file_types' => (new GetCustomerFileTypes)->execute(true),
        ]);
    }

    //  Ajax call te get JSON details of the link
    public function show($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        $linkDataObj = new GetFileLinkDetails($id);
        $linkData = $linkDataObj->execute(true);

        return $linkData;
    }

    //  Update the link's details
    public function update(UpdateFileLinkRequest $request, $id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        (new SetFileLinkDetails)->execute($request, $id);

        Log::info('File Link Updated by '.Auth::user()->full_name, ['link_id' => $id]);
        return response()->json(['success' => true]);
    }

    //  Retrieve the instructions attached to a link
    public function getInstructions($linkID)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return response()->json(['note' => (new GetFileLinkDetails($linkID))->getLinkInstructions()]);
    }

    //  Update the instructions attached to the link
    public function submitInstructions(UpdateFileLinkInstructionsRequest $request, $linkID)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name . '. Submitted Data - ', $request->toArray());

        (new SetFileLinkDetails)->setLinkInstructions($request, $linkID);

        return response()->json(['success' => true]);
    }

    //  Disable a file linke, but do not remove it (set the expire date to a previous date)
    public function disableLink($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        (new KillFileLink)->disableLink($id);

        Log::info('User '.Auth::user()->full_name.' disabled link ID - '.$id);
        return response()->json(['success' => true]);
    }

    //  Delete a file link
    public function destroy($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        (new KillFileLink)->deleteFileLink($id);

        Log::info('File link ID - '.$id.' deleted by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }
}
