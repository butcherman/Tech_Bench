<?php

namespace App\Exceptions\FileLink;

use App\Models\FileLink;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/*
|-------------------------------------------------------------------------------
| Exception is triggered when someone tries to visit a public file link
| that has expired.
|-------------------------------------------------------------------------------
*/

class FileLinkExpiredException extends Exception
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(protected FileLink $link)
    {
        parent::__construct();
    }

    public function report(): void
    {
        Log::alert('Someone is trying to visit an expired File Link URL', [
            'ip_address' => request()->ip(),
            'link_hash' => $this->link->link_hash,
        ]);
    }

    public function render(): Response
    {
        return Inertia::render('FileLink/Public/Expired')
            ->toResponse(request())
            ->setStatusCode(410);
    }
}
