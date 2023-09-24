<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
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
            "id"           => $this->id,
            "title"        => $this->title,
            "company_name" => $this->company_name,
            "logo"         => $this->logo,
            "still_work"   => $this->still_work,
            "location"     => $this->location,
            "description"  => $this->description,
            "started"      => $this->started,
            "ended"        => $this->ended,
        ];
    }
}
