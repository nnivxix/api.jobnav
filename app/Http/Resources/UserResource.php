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
            'avatar'      => $this->profile?->avatar,
            'cover'       => $this->profile?->cover,
            'user_skills' => $this->profile?->user_skills,
            'experiences' => ExperienceResource::collection($this->experiences),
        ];
    }
}
