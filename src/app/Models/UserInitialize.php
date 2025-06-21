<?php

namespace App\Models;

use App\Observers\UserInitializeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

#[ObservedBy([UserInitializeObserver::class])]
class UserInitialize extends Model
{
    use HasFactory;
    use Prunable;

    /** @var array<int, string> */
    protected $guarded = ['created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['id', 'created_at', 'updated_at'];

    /*
    |---------------------------------------------------------------------------
    | Route Model Binding Key
    |---------------------------------------------------------------------------
    */
    public function getRouteKeyName(): string
    {
        return 'token';
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    /*
    |---------------------------------------------------------------------------
    | Prune models older than seven days
    |---------------------------------------------------------------------------
    */
    public function prunable(): Builder
    {
        Log::debug('Calling Prune User Initialize Links');

        return static::whereDate('created_at', '<', now()->subDays(7));
    }
}
