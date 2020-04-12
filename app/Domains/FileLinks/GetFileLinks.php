<?php

namespace App\Domains\FileLinks;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\FileLinks;

use App\Http\Resources\FileLinksCollection;

class GetFileLinks
{
    protected $id, $collection;

    public function __construct($id, $collection = false)
    {
        $id == 0 ? $this->id = Auth::user()->user_id : $this->id = $id;
        $this->collection = $collection;
    }

    public function execute()
    {
        $links = FileLinks::where('user_id', $this->id)
                    ->withCount('FileLinkFiles')
                    ->orderBy('expire', 'desc')->get();

        if ($this->collection) {
            return new FileLinksCollection($links);
        }

        return $links;
    }
}
