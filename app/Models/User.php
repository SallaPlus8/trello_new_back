<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Workspace;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Laratrust\Contracts\LaratrustUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements LaratrustUser, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndPermissions, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        "created_at",
        "updated_at"

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

        ########## Start Relations ################

        public function workspaces()
        {
            return $this->belongsToMany(Workspaces::class,'workspace_members','user_id','workspace_id');
        }

        ########## End Relations ################

}
