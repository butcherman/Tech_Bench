<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerNote;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;


class DownloadNoteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Customer $customer, CustomerNote $note): Response
    {
        Log::info('Customer Note ID ' . $note->note_id . ' downloaded by ' .
            $request->user()->username);

        return Pdf::loadView('pdf.customer_note', [
            'customer' => $customer,
            'note' => $note->load(['CustomerEquipment', 'Sites']),
        ])->setOption(['isRemoteEnabled' => true])->stream();
    }
}
