<?php

namespace App\Models;

use App\Observers\FileLinkFileObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([FileLinkFileObserver::class])]
class FileLinkFile extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'link_file_id';
}
