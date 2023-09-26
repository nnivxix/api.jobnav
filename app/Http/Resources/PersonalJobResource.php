<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonalJobResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'uuid'               => $this->uuid,
            "company_id"         => $this->company_id,
            "title"              => $this->title,
            "posted_at"          => $this->posted_at,
            "position"           => $this->position,
            "location"           => $this->location,
            "salary"             => $this->salary,
            'is_applied_by_user' => $this->is_applied_by_user,
            "description"        => $this->description,
            'applicants'         => AuthenticatedUserResource::collection($this->applicants)
        ];
    }
}
