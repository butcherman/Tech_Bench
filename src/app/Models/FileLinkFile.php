<?php

namespace App\Models;

use App\Observers\FileLinkFileObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[ObservedBy([FileLinkFileObserver::class])]
class FileLinkFile extends Pivot
{
    use HasFactory;

    /** @var string */
    protected $table = 'file_link_files';

    /** @var string */
    protected $primaryKey = 'link_file_id';
}
