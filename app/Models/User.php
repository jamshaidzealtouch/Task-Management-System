<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Project;
use App\Models\Organization;
use App\Enums\UserRole;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'organization_id',
    ];

    /**
     * The attributes that should be hidden.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * User's projects.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Organizations owned by the user.
     */
    public function organizations()
    {
        return $this->hasMany(
            Organization::class,
            'owner_id'
        );
    }

    /**
     * Organization to which the user belongs.
     */
    public function organization()
    {
        return $this->belongsTo(
            Organization::class
        );
    }

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }
}