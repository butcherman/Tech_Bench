<?php

namespace App\Console\Commands\Auth;

use App\Models\UserVerificationCode;
use Carbon\Carbon;
use Illuminate\Console\Command;

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

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $codeList = UserVerificationCode::where(
            'updated_at',
            '<',
            Carbon::now()->subMinutes(15)
        )->get();

        $count = $codeList->count();

        foreach ($codeList as $code) {
            $code->delete();
        }

        $this->line('Cleared '.$count.' verification codes.');
    }
}
