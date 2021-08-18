<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;

use App\Models\TechTip;
use App\Models\TechTipBookmark;

class GetDetailsController extends Controller
{
    /**
     * Get the Admin Details of a Tech Tip
     */
    public function __invoke($tipId)
    {
        $tip       = TechTip::find($tipId);
        $favorites = TechTipBookmark::where('tip_id', $tipId)->count();

        return [
            'author'      => $tip->CreatedBy->full_name,
            'dateCreated' => date('M d, Y', strtotime($tip->created_at)),
            'lastEdited'  => $tip->updated_id !== null ? date('M d, Y', strtotime($tip->updated_at)) : null,
            'editedBy'    => $tip->updated_id !== null ? $tip->UpdatedBy->full_name : null,
            'views'       => $tip->views,
            'favorites'   => $favorites,
            'isPinned'    => $tip->sticky ? 'Yes' : 'No',
        ];
    }
}
