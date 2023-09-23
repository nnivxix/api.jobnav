<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'uuid'        => $this->uuid,
            "company_id"  => $this->company_id,
            "title"       => $this->title,
            "posted_at"   => $this->posted_at,
            "position"    => $this->position,
            "location"    => $this->location,
            "salary"      => $this->salary,
            "description" => $this->description,
            "company"     => $this->company, // make resource company
        ];
    }
}
