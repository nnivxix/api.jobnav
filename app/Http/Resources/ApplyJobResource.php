<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplyJobResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'cover_letter'   => $this->cover_letter,
            'attachment_url' => $this->attachment_url,
            'status'         => $this->status,
            'created_at'     => $this->created_at
        ];
    }
}
