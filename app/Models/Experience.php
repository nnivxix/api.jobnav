<?php

namespace App\Models;

use App\Models\Concerns\HasUrlAsset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    use HasUrlAsset;

    protected $casts = [
        'started' => 'date',
        'ended'   => 'date',
        'still_work' => 'boolean'
    ];
    protected $guarded = [
        'id',
    ];
}
