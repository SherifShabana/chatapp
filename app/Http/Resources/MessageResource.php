<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'content' => $this->content,
            'user_name' => $this->user->name ?? "",
            'user_id' => $this->user->id ?? "",
            'channel_id' => $this->channel->id ?? "",
            'channel_name' => $this->channel->name ?? "",
            'channel' => $this->whenLoaded('channel', new ChannelResource($this->channel)),
            'user' => $this->whenLoaded('user', new UserResource($this->user)),
        ];
    }
}
