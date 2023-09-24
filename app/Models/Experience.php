<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $casts = [
        'started' => 'date',
        'ended'   => 'date',
        'still_work' => 'boolean'
    ];
    protected $guarded = [
        'id',
    ];
}
