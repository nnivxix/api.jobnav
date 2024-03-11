<?php

namespace App\Builders;

use App\Models\ApplyJob;
use Illuminate\Database\Eloquent\Builder;

class JobBuilder extends Builder
{
    public function published(): self
    {
        return $this->whereNotNull('posted_at');
    }
    public function remoteJobs(): self
    {
        return $this->where('is_remote', 1);
    }
    public function appliedJobs(): self
    {
        if (auth()->check()) {
            $appliedJobIds = ApplyJob::query()->where('user_id', auth()->id())->pluck('job_id');
            return $this->whereNotIn('id', $appliedJobIds);
        }
        return $this;
    }
}
