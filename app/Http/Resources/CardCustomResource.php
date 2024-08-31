<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardCustomResource extends JsonResource
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
            'the_board_name' => $this->list->board->name,
            'the_list_name' => $this->list->title,
            'position' => $this->position,
            'text' => $this->text,
            'completed' => $this->completed,
            // 'description' => $this->description,
            // 'start_time' => $this->start_time,
            // 'end_time' => $this->end_time,
            // 'user_name' => $this->user->name,
            // 'photo' => $this->photo,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            // 'comments' => CommentResource::collection($this->whenLoaded('comments')),
            // 'labels' => LabelResource::collection($this->whenLoaded('labels')),
        ];
    }
}
