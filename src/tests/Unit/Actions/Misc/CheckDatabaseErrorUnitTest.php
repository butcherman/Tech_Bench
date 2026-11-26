<?php

namespace Tests\Unit\Actions\Misc;

use App\Exceptions\Database\DuplicateDataException;
use App\Exceptions\Database\RecordInUseException;
use App\Facades\DbException;
use App\Models\TechTip;
use App\Models\User;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class CheckDatabaseErrorUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | check()
    |---------------------------------------------------------------------------
    */
    public function test_check_record_in_use(): void
    {
        $user = User::factory()->create();
        TechTip::factory()->create(['user_id' => $user]);

        $this->expectException(RecordInUseException::class);

        try {
            $user->forceDelete();
        } catch (QueryException $e) {
            DbException::check($e);
        }
    }

    public function test_check_duplicate_data(): void
    {
        $user = User::factory()->create();
        $test = User::factory()->make();

        $test->user_id = $user->user_id;

        $this->expectException(DuplicateDataException::class);

        try {
            $test->save();
        } catch (QueryException $e) {
            DbException::check($e);
        }
    }
}
