<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CsrRequest;
use App\Http\Requests\Admin\SecurityRequest;
use App\Models\AppSettings;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\SslCertificate\SslCertificate;

/**
 * SSL Certificate Operations
 */
class SecurityController extends Controller
{
    /**
     * Landing page to show existing SSL Cert and offer options for replacing/removing
     */
    public function index()
    {
        $this->authorize('viewAny', AppSettings::class);

        try {
            // Attempt to pull current SSL Cert and validate
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

    /**
     * Form to load a new SSL Certificate
     */
    public function create()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Security/Create');
    }

    /**
     * Save new SSL Certificate
     */
    public function store(SecurityRequest $request)
    {
        $valid = $request->processCertificate();

        return $valid;
    }

    /**
     * Form to generate a new CSR request for SSL Certificate
     */
    public function edit()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Security/Edit');
    }

    /**
     * Use provided form to generate CSR request
     */
    public function update(CsrRequest $request)
    {
        $csr = $request->processCsrRequest();

        return Inertia::render('Admin/Security/Edit', [
            'csr-request' => $csr,
        ]);
    }

    /**
     * Delete existing SSL Certification.
     * New Self Signed Cert will be created on reboot if one is not uploaded
     */
    public function destroy(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        // Remove both SSL Cert and Private Key
        Storage::disk('security')->delete('server.crt');
        Storage::disk('security')->delete('private/server.key');

        Log::alert('SSL Certificate and Private key have been deleted by '.$request->user()->username);

        return back()->with('warning', 'SSL Certificate Deleted.  Please reboot for changes to take affect');
    }
}
