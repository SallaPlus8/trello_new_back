<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $guarded = [];


    ########## Relations ################

    public function lists()
    {
        $this->hasMany(TheList::class,'list_id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class,'board_members','board_id','user_id');
    }
    ########## Relations ################
}
