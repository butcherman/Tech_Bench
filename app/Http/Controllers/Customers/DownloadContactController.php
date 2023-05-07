<?php

namespace App\Http\Controllers\Customers;

use App\Actions\BuildContactVCard;
use App\Http\Controllers\Controller;
use App\Models\CustomerContact;

class DownloadContactController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CustomerContact $contact)
    {
        $vCard = new BuildContactVCard;
        return $vCard->buildCustomerContact($contact)->download();
    }
}
