<?php

namespace App\Builders;

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
}
