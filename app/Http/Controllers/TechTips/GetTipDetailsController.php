<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;

use App\Models\TechTip;
use App\Models\UserTechTipBookmark;

class GetTipDetailsController extends Controller
{
    /**
     * Get Admin details of a Tech Tip
     */
    public function __invoke($tipId)
    {
        $this->authorize('manage', TechTip::class);

        $tip       = TechTip::find($tipId);
        $favorites = UserTechTipBookmark::where('tip_id', $tipId)->count();

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
