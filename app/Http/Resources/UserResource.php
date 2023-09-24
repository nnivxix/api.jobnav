<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'        => $this->name,
            'username'    => $this->username,
            'header'      => $this->profile?->header,
            'avatar'      => $this->profile?->avatar,
            'cover'       => $this->profile?->cover,
            'user_skills' => $this->profile?->user_skills,
        ];
    }
}
