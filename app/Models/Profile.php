<?php

namespace App\Models;

use App\Models\Concerns\HasUrlAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;
    use HasUrlAsset;

    public function userSkills(): Attribute
    {
        return Attribute::make(
            get: fn () => explode(",", str_replace(' ', '', $this->skills))
        );
    }


    /**
     * Accessors
     */
}
