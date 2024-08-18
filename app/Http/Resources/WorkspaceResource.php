<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'workspace_id' => $this->id,
            'workspace_name' => $this->name,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'boards_of_the_workspace' => BoardResource::collection($this->whenLoaded('boards')), // Include boards
        ];
    }
}
