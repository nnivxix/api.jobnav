<?php

namespace App\Models;

use App\Models\Concerns\HasUrlAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($company) {
            $jobs = $company->jobs;

            foreach ($jobs as $job) {
                foreach ($job->applyJobs as $applyJob) {
                    if (Storage::disk('public')->exists($applyJob->attachment)) {
                        Storage::disk('public')->delete($applyJob->attachment);
                    }
                }
            }
        });
    }


    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function applyJobs()
    {
        return $this->hasManyThrough(ApplyJob::class, Job::class, 'company_id', 'job_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owned_by', 'id');
    }

    public function isOwned($id): bool
    {
        return auth()->user()->companies->where('id', $id)->count() > 0;
    }
}
