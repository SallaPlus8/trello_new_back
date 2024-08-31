<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'workspace_id' => $this->workspace_id,
            'board_id'        => $this->id,
            'board_name'      => $this->name,
            'board_background'      => $this->photo,
            'lists_of_the_board'     => TheListResource::collection($this->whenLoaded('lists')),
            'users' => UserResource::collection($this->whenLoaded('users')), // Include boards
        ];
    }
}
