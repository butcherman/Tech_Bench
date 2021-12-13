<?php

namespace App\Http\Controllers\Admin\Email;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use App\Models\AppSettings;
use App\Http\Controllers\Controller;
use App\Notifications\SendTestEmail;

class SendTestEmailController extends Controller
{
    /**
     * Send a test email
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        try
        {
            Notification::send(Auth::user(), new SendTestEmail);

            return [
                'success' => true,
            ];
        }
        catch(Exception $e)
        {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
