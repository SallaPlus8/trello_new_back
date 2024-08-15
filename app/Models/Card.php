<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $guarded = [];

    ####### start Relations #############
    public function list()
    {
        return $this->belongsTo(TheList::class,'the_list_id','id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    ####### end Relations #############

}
