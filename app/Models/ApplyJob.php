<?php

namespace App\Models;

use App\Enums\StatusJobEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplyJob extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => StatusJobEnum::class,
    ];
}
