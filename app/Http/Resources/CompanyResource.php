<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            "name"         => $this->name,
            "slug"         => $this->slug,
            "avatar"       => $this->avatar,
            "cover"        => $this->cover,
            "owner"        => UserResource::make($this->owner),
            "about"        => $this->about,
            "location"     => $this->location,
            "full_address" => $this->full_address,
            "website"      => $this->website,
            "posted_at"    => $this->posted_at,
        ];
    }
}
