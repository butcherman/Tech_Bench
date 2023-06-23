<?php

namespace App\Http\Controllers\Admin\Config;

use Spatie\SslCertificate\SslCertificate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SecurityRequest;
use App\Models\AppSettings;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SecurityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', AppSettings::class);

        try {
            $cert = SslCertificate::createFromFile(Storage::disk('security')->path('server.crt'));
        } catch (Exception) {
            $cert = null;
            Log::critical('Tried to load non-existent SSL Certificate.  Please reboot to generate self signed certificate');
        }

        return Inertia::render('Admin/Security/Index', [
            'cert' => $cert ? Storage::disk('security')->get('server.crt') : null,
            'is-valid' => $cert ? $cert->isValid() : false,
            'issuer' => $cert ? $cert->getIssuer() : null,
            'expires' => $cert ? $cert->expirationDate()->toFormattedDateString() : null,
            'signature' => $cert ? $cert->getSignatureAlgorithm() : null,
            'organization' => $cert ? $cert->getOrganization() : null,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Security/Create');
    }

    public function store(SecurityRequest $request)
    {
        $valid = $request->processCertificate();

        return $valid;
    }

    public function edit()
    {
        return 'generate csr';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        Storage::disk('security')->delete('server.crt');
        Storage::disk('security')->delete('private/server.key');

        Log::alert('SSL Certificate and Private key have been deleted by '.$request->user()->username);
        return back()->with('warning', 'SSL Certificate Deleted.  Please reboot for changes to take affect');
    }
}
