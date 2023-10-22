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
            "name"       => $this->name,
            "slug"       => $this->slug,
            "avatar"     => $this->avatar,
            "cover"      => $this->cover,
            "owner"      => UserResource::make($this->owner),
            "about"      => $this->about,
            "location"   => $this->location,
            "address"    => $this->address,
            "website"    => $this->website,
            "jobs_count" => $this->jobs_count,
        ];
    }
}
