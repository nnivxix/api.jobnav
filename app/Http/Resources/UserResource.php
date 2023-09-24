<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'name'        => $this->name,
            'username'    => $this->username,
            'header'      => $this->profile?->header,
            'avatar'      => $this->profile?->avatar_url,
            'cover'       => $this->profile?->cover_url,
            'user_skills' => $this->profile?->user_skills,
            'experiences' => ExperienceResource::collection($this->whenLoaded('experiences')),
        ];
    }
}
