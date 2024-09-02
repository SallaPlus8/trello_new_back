<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'file_path',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
