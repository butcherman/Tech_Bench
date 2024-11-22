<?php

namespace App\Console\Commands\Auth;

use App\Models\UserVerificationCode;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ClearValidationCodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:clear-validation-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear any authorization codes more than 15 minutes old';

    /*
    |---------------------------------------------------------------------------
    | Command will delete any User Authorization Codes more than 15 minutes old
    |---------------------------------------------------------------------------
    */
    public function handle()
    {
        Log::debug('Running auth:clear-validation-codes command');

        $codeList = UserVerificationCode::where(
            'updated_at',
            '<',
            Carbon::now()->subMinutes(15)
        )->get();

        $count = $codeList->count();

        UserVerificationCode::destroy($codeList);

        Log::debug('Cleared '.$count.' verification codes.');

        $this->line('Cleared '.$count.' verification codes.');
    }
}
