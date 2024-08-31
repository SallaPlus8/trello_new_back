<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommentResource extends JsonResource
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
            'comment_id' => $this->id,
            // 'card_id' => $this->card_id,
            'comment' => $this->comment,
            'photo' => $this->photo ? Storage::url($this->photo): null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
