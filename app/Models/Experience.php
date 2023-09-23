<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $cast = [
        'started' => 'date',
        'ended'   => 'date',
    ];
    protected $guarded = [
        'id',
    ];
}
