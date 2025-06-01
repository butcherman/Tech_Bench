<?php

namespace App\Models;

use App\Observers\TechTipCommentFlagObserver;
use App\Traits\Models\HasUser;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([TechTipCommentFlagObserver::class])]
class TechTipCommentFlag extends Model
{
    use BroadcastsEvents;
    use HasUser;

    /** @var array<int, string> */
    protected $guarded = ['id', 'updated_at', 'created_at'];

    /** @var array<int, string> */
    protected $appends = ['flagged_by'];

    /** @var array<int, string> */
    protected $hidden = ['User'];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:M d, Y',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function flaggedBy(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->User->full_name,
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function TechTipComment(): BelongsTo
    {
        return $this->belongsTo(
            TechTipComment::class,
            'comment_id',
            'comment_id'
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Broadcasting
    |---------------------------------------------------------------------------
    */

    public function broadcastOn(string $event): array
    {
        return  [
            new PrivateChannel('tech-tips.' . $this->TechTipComment->tip_id),
        ];
    }

    public function newBroadcastableModelEvent(string $event): BroadcastableModelEventOccurred
    {
        return (new BroadcastableModelEventOccurred(
            $this,
            $event
        ))->dontBroadcastToCurrentUser();
    }
}
