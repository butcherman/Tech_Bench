<?php

namespace App\Exceptions\FileLink;

use App\Models\FileLink;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class FileLinkExpiredException extends Exception
{
    protected $request;

    protected $link;

    public function __construct(Request $request, FileLink $link)
    {
        parent::__construct();
        $this->request = $request;
        $this->link = $link;
    }

    public function report()
    {
        Log::alert('Someone is trying to visit an expired File Link URL', [
            'ip_address' => $this->request->ip(),
            'link_hash' => $this->link->link_hash,
        ]);
    }

    public function render()
    {
        return Inertia::render('Public/FileLinks/Expired')
            ->toResponse($this->request)
            ->setStatusCode(410);
    }
}
