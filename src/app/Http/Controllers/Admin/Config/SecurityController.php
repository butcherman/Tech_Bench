<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SecurityRequest;
use App\Models\AppSettings;
use App\Service\CertificateService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\SslCertificate\SslCertificate;

class SecurityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', AppSettings::class);
        $certObj = new CertificateService;

        return Inertia::render('Admin/Security/Index', [
            'cert' => $certObj->getCertText(),
            'data' => $certObj->getCertData(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('viewAny', AppSettings::class);
        $certObj = new CertificateService;

        return Inertia::render('Admin/Security/Create', [
            'has-key' => $certObj->verifyKeyExists(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SecurityRequest $request)
    {
        $certObj = new CertificateService(false);
        $certObj->processNewCertificate($request);

        if (!$certObj->wasSuccessful()) {
            return back()->withErrors($certObj->getMessage());
        }

        return redirect(route('admin.security.index'))
            ->with('success', __('admin.security.updated'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Admin/Security/Edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }
}
