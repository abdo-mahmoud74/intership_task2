<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'description' => $this->description,
            'status' => $this->status,
            'project_id' => $this->project_id,
            'created_by'=> [
                'id'=> $this->creator->id,
                'name'=> $this->creator->name,
                'email'=> $this->creator->email,
            ],
            'assigned_to'=> [
                'id'=> $this->user->id,
                'name'=> $this->user->name,
                'email'=> $this->user->email,
            ],

            'keywords'=> $this->tags->pluck('name'),

        ];
    }
}
