<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Models\FileLink;
use Illuminate\Http\RedirectResponse;

class ExtendLinkController extends Controller
{
    /**
     * Extend the Expire date on a file link by 30 days
     */
    public function __invoke(FileLink $link): RedirectResponse
    {
        $this->authorize('update', $link);

        $link->extendLink();

        return back()
            ->with(
                'success',
                'Link Extended until '.$link->expire->format('M d, Y')
            );
    }
}
