<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid'               => $this->uuid,
            "company_id"         => $this->company_id,
            "title"              => $this->title,
            "posted_at"          => $this->posted_at,
            "location"           => $this->location,
            "is_remote"          => $this->isRemoteJob(),
            "salary"             => $this->salary,
            'is_applied_by_user' => $this->is_applied_by_user,
            "description"        => $this->description,
            "company"            => CompanyResource::make($this->whenLoaded('company')),
            'applicant'          => ApplyJobResource::make($this->whenLoaded('applyJob'))
        ];
    }
}
