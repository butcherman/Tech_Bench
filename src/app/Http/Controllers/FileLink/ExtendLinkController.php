<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Models\FileLink;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExtendLinkController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, FileLink $link)
    {
        $this->authorize('update', $link);

        $currentExpire = Carbon::parse($link->expire);
        $link->update([
            'expire' => $currentExpire->addDays(30),
        ]);

        Log::info('A File Link has been extended 30 days by '.$request->user()->username, $link->toArray());

        return back()
            ->with(
                'success',
                'Link Extended until '.$link->expire->format('M d, Y')
            );
    }
}
