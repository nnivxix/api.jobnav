<?php

namespace App\Models;

use App\Models\Concerns\HasUrlAsset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;
    use HasUrlAsset;

    protected $cast = [
        'posted_at' => 'date',
    ];
    protected $guarded = [
        'id',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owned_by', 'id');
    }
}
