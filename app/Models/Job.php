<?php

namespace App\Models;

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
        // ->where('job_id', $this->id)
    }
}
