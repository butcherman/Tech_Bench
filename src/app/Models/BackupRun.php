<?php

namespace App\Models;

use App\Enums\BackupRunStatus;
use App\Enums\BackupType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupRun extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'backup_id';

    /** @var array<int, string> */
    protected $guarded = ['backup_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['backup_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $appends = ['duration'];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'type' => BackupType::class,
            'status' => BackupRunStatus::class,
            'started_at' => 'datetime:M d, Y | h:m:s A',
            'completed_at' => 'datetime:M d, Y | h:m:s A',
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function duration(): Attribute
    {
        $started = Carbon::parse($this->started_at);
        $completed = Carbon::parse($this->completed_at);

        return Attribute::make(
            get: fn () => $started->diffInSeconds($completed),
        );
    }
}
