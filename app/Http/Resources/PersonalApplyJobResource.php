<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonalApplyJobResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid'           => $this->uuid,
            'cover_letter'   => $this->cover_letter,
            'attachment_url' => $this->attachment_url,
            'status'         => $this->status,
            'updated_at'     => $this->updated_at,
            'job'            => JobResource::make($this->job),
            'company'        => CompanyResource::make($this->job->company),
        ];
    }
}
