<?php

namespace App\Models;

use App\Enums\StatusJobEnum;
use App\Models\Concerns\HasUrlAsset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplyJob extends Model
{
    use HasFactory;
    use HasUrlAsset;

    protected $casts = [
        'status' => StatusJobEnum::class,
    ];
}
