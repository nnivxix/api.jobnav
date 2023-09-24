<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasUrlAsset
{
    public function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => asset(Storage::url($this->avatar))
        );
    }
    public function coverUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => asset(Storage::url($this->cover))
        );
    }
}
