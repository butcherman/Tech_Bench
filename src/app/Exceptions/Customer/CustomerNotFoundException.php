<?php

namespace App\Exceptions\Customer;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| Exception notes that a customer slug or customer ID does not exist in
| the database.  A custom customer not found page will be rendered.
|-------------------------------------------------------------------------------
*/

class CustomerNotFoundException extends Exception
{
    public function report(): void
    {
        Log::warning('Unable to find request customer page', [
            'user' => request()->user()->username,
            'path' => request()->path(),
        ]);
    }

    public function render(): RedirectResponse
    {
        return redirect(route('customers.not-found'));
    }
}
