<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    public function userSkills(): Attribute
    {
        return Attribute::make(
            get: fn () => explode(",", str_replace(' ', '', $this->skills))
        );
    }
}
