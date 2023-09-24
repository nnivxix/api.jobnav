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
            "uuid"          => $this->uuid,
            "title"         => $this->title,
            "company_name"  => $this->company_name,
            "logo_url"      => $this->logo_url,
            "still_working" => $this->still_working,
            "location"      => $this->location,
            "description"   => $this->description,
            "started"       => $this->started,
            "ended_status"  => $this->ended_status,
            "time_span"     => $this->time_span,
        ];
    }
}
