<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\ModelFlags\Models\Concerns\HasFlags;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFlags;
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function (User $user) {
            $user->profile()->create();
        });
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'owned_by', 'id');
    }
    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }
    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'apply_jobs')->withTimestamps();
    }
    public function jobPosts()
    {
        return $this->hasManyThrough(Job::class, Company::class, 'owned_by', 'company_id');
    }

    public function getRouteKeyName()
    {
        return 'username';
    }
}
