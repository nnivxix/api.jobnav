<?php

namespace App\Models\Concerns;

use App\Models\User;
use App\Models\ApplyJob;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasApplyJobs
{
    public function applyJobs()
    {
        return $this->hasMany(ApplyJob::class, 'job_id');
    }

    public function applicants()
    {
        return $this->belongsToMany(User::class, 'apply_jobs', 'job_id', 'user_id');
    }

    public function isAppliedByUser(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (auth('sanctum')->check() || auth()->check()) {
                    return $this->applicants->contains(auth('sanctum')->user());
                } else {
                    return false;
                }
            }
        );
    }
}
