<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArchiveResource extends JsonResource
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
            'id' => $this->id,
            'message_id' => $this->message_id,
            'content' => $this->content,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'file' => $this->file,
            'deleted_at' => $this->deleted_at
        ];
    }
}
