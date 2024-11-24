<?php

namespace App\Models;

use App\Observers\TechTipCommentObserver;
use App\Traits\Models\HasUser;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([TechTipCommentObserver::class])]
class TechTipComment extends Model
{
    use HasFactory;
    use HasUser;

    /** @var string */
    protected $primaryKey = 'comment_id';

    /** @var array<int, string> */
    protected $guarded = ['id', 'updated_at', 'created_at'];

    /** @var array<int, string> */
    protected $appends = ['author', 'is_flagged'];

    /** @var array<int, string> */
    protected $hidden = ['User', 'Flags'];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
            'flagged' => 'boolean',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function isFlagged(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->Flags->count() > 0 ? true : false,
        );
    }

    public function getAuthor(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->User->full_name,
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function TechTip(): BelongsTo
    {
        return $this->belongsTo(TechTip::class, 'tip_id', 'tip_id');
    }

    public function Flags(): HasMany
    {
        return $this->hasMany(
            TechTipCommentFlag::class,
            'comment_id',
            'comment_id'
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Model Methods
    |---------------------------------------------------------------------------
    */

    /**
     * Flag a comment as inappropriate
     */
    public function flagComment(User $user): void
    {
        $this->Flags()->save(new TechTipCommentFlag([
            'user_id' => $user->user_id,
            'comment_id' => $this->comment_id,
        ]));
    }
}
