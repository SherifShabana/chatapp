<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MessageResource extends JsonResource
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
            'content' => $this->content ?? "",
            'type' => $this->type,
            'user_id' => $this->user->id ?? "",
            'user_name' => $this->user->name ?? "",
            'channel_id' => $this->channel->id ?? "",
            'channel_name' => $this->channel->name ?? "",
            'created_at' => $this->created_at->format("H:i a") ?? "", //7:30 am
            /* 'file' => $this->file != null ? url(Storage::url($this->file ?? "")) : "", */

            /* 'channel' => $this->whenLoaded('channel', new ChannelResource($this->channel)),
            'user' => $this->whenLoaded('user', new UserResource($this->user)), */
        ];
    }
}
