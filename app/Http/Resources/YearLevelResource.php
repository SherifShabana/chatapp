<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class YearLevelResource extends JsonResource
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
            'name' => $this->name,
            'department' => $this->whenLoaded('department', new DepartmentResource($this->department)),
        ];
    }
}
