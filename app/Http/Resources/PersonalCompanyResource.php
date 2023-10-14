<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonalCompanyResource extends JsonResource
{

    public function toArray($request)
    {
        return  [
            "id"           => $this->id,
            "name"         => $this->name,
            "slug"         => $this->slug,
            "avatar_url"   => $this->avatar_url,
            "cover_url"    => $this->cover_url,
            "about"        => $this->about,
            "location"     => $this->location,
            "full_address" => $this->full_address,
            "website"      => $this->website,
            "posted_at"    => $this->posted_at,
        ];;
    }
}
