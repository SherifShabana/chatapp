<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'username' => $this->username,
            'section_id' => $this->section->id ?? "",
            'section_name' => $this->section->name ?? "",
            'year_level_name' => $this->section->yearLevel->name ?? "",
            'department_name' => $this->section->yearLevel->department->name ?? "",
            /*  'section' => $this->whenLoaded('section', new SectionResource($this->section)), */
        ];
    }
}
