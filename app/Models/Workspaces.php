<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspaces extends Model
{
    use HasFactory;

    protected $fillable= ['name'];

    ########## Start Relations ################
    
    public function users()
    {
        return $this->belongsToMany(User::class,'workspace_members','workspace_id','user_id')->withPivot('added_at');
    }
    public function boards()
    {
        return $this->hasMany(Board::class,'workspace_id','id');
    }
    ########## End Relations ################

}
