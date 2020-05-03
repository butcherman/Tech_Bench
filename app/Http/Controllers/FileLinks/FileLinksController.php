<?php

namespace App\Http\Controllers\FileLinks;

use App\Http\Controllers\Controller;

use App\Domains\FileLinks\GetFileLinks;
use App\Domains\FileLinks\KillFileLink;
use App\Domains\FileLinks\GetFileLinkDetails;
use App\Domains\FileLinks\SetFileLinkDetails;

use App\Http\Requests\FileLinkCreateRequest;
use App\Http\Requests\FileLinkUpdateRequest;
use App\Http\Requests\FileLinkInstructionsRequest;

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
        return view('links.index');
    }

    //  Ajax call to show the links for a specific user
    public function find($id)
    {
        //  If the user is trying to access the links of another user, they must have the proper permissions permissions
        if($id != 0)
        {
            $this->authorize('hasAccess', 'manage_users');
        }

        return (new GetFileLinks($id, true))->execute();
    }

    //  Create a new file link form
    public function create()
    {
        return view('links.newLink');
    }

    //  Submit the new file link form
    public function store(FileLinkCreateRequest $request)
    {
        $linkData = (new SetFileLinkDetails)->processNewLink($request);

        // return response()->json($linkData);
        return response()->json([
            'success' => $linkData ? true : false,
            'link_id' => $linkData ? $linkData : false,
        ]);
    }

    //  Show details about a file link
    public function details($id, /** @scrutinizer ignore-unused */ $name)
    {
        $linkDataObj = new GetFileLinkDetails($id);

        //  If the link is invalid, return an error page
        if(!$linkDataObj->isLinkValid())
        {
            return view('links.badLink');
        }

        return view('links.details', [
            'details'    => $linkDataObj->execute(true),
        ]);
    }

    //  Ajax call te get JSON details of the link
    public function show($id)
    {
        $linkDataObj = new GetFileLinkDetails($id);
        $linkData = $linkDataObj->execute(true);
        return $linkData;
    }

    //  Update the link's details
    public function update(FileLinkUpdateRequest $request, $id)
    {
        (new SetFileLinkDetails)->updateLink($request, $id);
        return response()->json(['success' => true]);
    }

    //  Retrieve the instructions attached to a link
    public function getInstructions($linkID)
    {
        return response()->json(['note' => (new GetFileLinkDetails($linkID))->getLinkInstructions()]);
    }

    //  Update the instructions attached to the link
    public function submitInstructions(FileLinkInstructionsRequest $request, $linkID)
    {
        (new SetFileLinkDetails)->setLinkInstructions($request, $linkID);
        return response()->json(['success' => true]);
    }

    //  Disable a file linke, but do not remove it (set the expire date to a previous date)
    public function disableLink($id)
    {
        (new KillFileLink)->disableLink($id);
        return response()->json(['success' => true]);
    }

    //  Delete a file link
    public function destroy($id)
    {
        (new KillFileLink)->deleteFileLink($id);
        return response()->json(['success' => true]);
    }
}
