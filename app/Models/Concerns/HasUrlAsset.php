<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasUrlAsset
{
    public function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->avatar ? asset(Storage::url($this->avatar)) : null
        );
    }
    public function coverUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->cover ? asset(Storage::url($this->cover)) : null
        );
    }
    public function logoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->logo ? asset(Storage::url($this->logo)) : null
        );
    }
    public function attachmentUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attachment ? asset(Storage::url($this->attachment)) : null
        );
    }
}
