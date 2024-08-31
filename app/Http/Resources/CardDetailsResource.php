<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CardDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // 'the_list_id' => $this->the_list_id,
            'text' => $this->text,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'user_name' => $this->user->name,
            'photo' => $this->photo ? Storage::url($this->photo): null,
            'color' => $this->color ,
            'completed' => $this->completed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'labels' => LabelResource::collection($this->whenLoaded('labels')),
        ];
    }
}
