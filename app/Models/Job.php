<?php

namespace App\Models;

use App\Builders\JobBuilder;
use App\Models\Concerns\HasApplyJobs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;
    use HasApplyJobs;

    protected $cast = [
        'posted_at' => 'date'
    ];
    protected $guarded = [
        'id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($job) {
            foreach ($job->applyJobs as $applyJob) {
                if (Storage::disk('public')->exists($applyJob->attachment)) {
                    Storage::delete('public/' . $applyJob->attachment);
                }
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function applyJob()
    {
        return $this->hasOne(ApplyJob::class, 'job_id', 'id')
            ->where('user_id', auth('sanctum')->user()->id ?? null);
    }

    /**
     * checkAuthorization
     * is user has applied the job or owner of the job?
     * this function should be set on policy
     */
    public function checkAuthorization()
    {
        abort_if(
            $this->isAppliedByUser || $this->company->owned_by == auth()->user()->id,
            response()->json([
                'message' => "Forbidden you can't apply twice."
            ], 403)
        );
    }

    public function isRemoteJob()
    {
        return boolval($this->is_remote);
    }

    public function newEloquentBuilder($query): JobBuilder
    {
        return new JobBuilder($query);
    }
}
