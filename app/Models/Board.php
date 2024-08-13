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

    ########## Relations ################
}
