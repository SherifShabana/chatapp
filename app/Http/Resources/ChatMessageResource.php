<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
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
            'content' => $this->content,
            'user_id' => $this->user->id ?? "",
            'user_name' => $this->user->name ?? "",
            'created_at' => $this->created_at->format("h:i a") ?? "",//7:30 am
            /* 'file' => $this->file != null ? url(Storage::url($this->file ?? "")) : "", */
            /* 'channel' => $this->whenLoaded('channel', new ChannelResource($this->channel)),
            'user' => $this->whenLoaded('user', new UserResource($this->user)), */
        ];
    }
}
