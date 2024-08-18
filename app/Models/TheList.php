<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheList extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table='the_lists';

    ######## start Relations ############

    public function board() {

        return $this->belongsTo(Board::class,'board_id','id');
    }
    public function cards()
    {
    return $this->hasMany(Card::class, 'the_list_id', 'id')->orderBy('position');
    }


    ######## End Relations ############
}
