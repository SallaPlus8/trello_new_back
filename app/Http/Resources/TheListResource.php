<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TheListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'board_id'  => $this->board_id,
            'list_id'        => $this->id,
            'list_title'     => $this->title,
            'cards_of_the_list' => CardResource::collection($this->whenLoaded('cards')),

        ];
    }
}
