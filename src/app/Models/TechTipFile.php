<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TechTipFile extends Pivot
{
    use HasFactory;

    /** @var bool */
    public $incrementing = true;

    /** @var string */
    protected $table = 'tech_tip_files';

    /** @var string */
    protected $primaryKey = 'tip_file_id';

    /** @var array<int, string> */
    protected $guarded = ['tip_file_it', 'created_at', 'updated_at'];
}
