<?php

// TODO - Refactor

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CsrRequest;
use App\Http\Requests\Admin\SecurityRequest;
use App\Models\AppSettings;
use App\Service\CertificateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class SecurityController extends Controller
{
    /**
     * Display a Current SSL Certificate
     */
    public function index(): Response
    {
        $this->authorize('viewAny', AppSettings::class);
        $certObj = new CertificateService;

        return Inertia::render('Admin/Security/Index', [
            'cert' => $certObj->getCertText(),
            'data' => $certObj->getCertData(),
        ]);
    }

    /**
     * Show the form for uploading a new SSL Certificate
     */
    public function create(): Response
    {
        $this->authorize('viewAny', AppSettings::class);
        $certObj = new CertificateService;

        return Inertia::render('Admin/Security/Create', [
            'has-key' => $certObj->verifyKeyExists(),
        ]);
    }

    /**
     * Store the uploaded SSL Certificate
     */
    public function store(SecurityRequest $request): RedirectResponse
    {
        $certObj = new CertificateService(false);
        $certObj->processNewCertificate($request);

        if (! $certObj->wasSuccessful()) {
            Log::critical('Uploading new Certificate Failed', [
                'message' => $certObj->getMessage(),
            ]);

            // TODO - Verify this shows properly
            return back()->withErrors(['cert_error' => $certObj->getMessage()]);
        }

        Log::info('New Certificate uploaded by '.$request->user()->username);

        return redirect(route('admin.security.index'))
            ->with('success', __('admin.security.updated'));
    }

    /**
     * Show the form for creating a SSL CSR Request
     */
    public function edit(): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Security/Edit');
    }

    /**
     * Generate a CSR Request
     */
    public function update(CsrRequest $request, string $id): Response
    {
        $certObj = new CertificateService(false);

        Log::info('New CSR Request generated by '.$request->user()->username);

        return Inertia::render('Admin/Security/Edit', [
            'csr-request' => $certObj->generateCsr($request),
        ]);
    }

    /**
     * Remove the Current SSL Certificate
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->authorize('update', AppSettings::class);

        $certObj = new CertificateService;
        $certObj->destroyCertificate();

        Log::alert('SSL Certificate deleted by '.$request->user()->username);

        return back()->with('warning', __('admin.security.deleted'));
    }
}