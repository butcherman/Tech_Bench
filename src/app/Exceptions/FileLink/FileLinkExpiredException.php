<?php

namespace App\Exceptions\FileLink;

use App\Models\FileLink;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class FileLinkExpiredException extends Exception
{
    /**
     * Exception is triggered when someone tries to visit a public file link
     * that has expired
     */
    public function __construct(protected Request $request, protected FileLink $link)
    {
        parent::__construct();
    }

    public function report(): void
    {
        Log::alert('Someone is trying to visit an expired File Link URL', [
            'ip_address' => $this->request->ip(),
            'link_hash' => $this->link->link_hash,
        ]);
    }

    public function render(): Response
    {
        return Inertia::render('Public/FileLinks/Expired')
            ->toResponse($this->request)
            ->setStatusCode(410);
    }
}
