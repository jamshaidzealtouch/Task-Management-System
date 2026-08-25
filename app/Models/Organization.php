<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Project;
use App\Models\Invitation;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'owner_id',
    ];

    public function owner()
    {
        return $this->belongsTo(
            User::class,
            'owner_id'
        );
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'organization_user'
        );
    }

    public function projects()
    {
        return $this->hasMany(
            Project::class,
            'organization_id'
        );
    }

    public function invitations()
    {
        return $this->hasMany(
            Invitation::class,
            'organization_id'
        );
    }
}