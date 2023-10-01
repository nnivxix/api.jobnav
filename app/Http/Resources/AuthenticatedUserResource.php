<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthenticatedUserResource extends JsonResource
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
            'id'          => $this->id,
            'email'       => $this->email,
            'name'        => $this->name,
            'username'    => $this->username,
            'bio'         => $this->profile?->bio,
            'avatar'      => $this->profile?->avatar_url,
            'cover'       => $this->profile?->cover_url,
            'user_skills' => $this->profile?->user_skills,
            'experiences' => ExperienceResource::collection($this->whenLoaded('experiences')),
            'jobs'        => JobResource::collection($this->whenLoaded('jobs')),
        ];
    }
}
