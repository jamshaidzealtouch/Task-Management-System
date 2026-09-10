<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;
use App\Models\User;
use App\Models\Project;

class Invitation extends Model
{
    protected $fillable = [
        'project_id',
        'invited_by',
        'user_id',
        'email',
        'role',
        'token',
        'status',
        'accepted_at',
        'expires_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    public function inviter()
    {
        return $this->belongsTo(
            User::class,
            'invited_by'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function organization()
    {
        return $this->hasOneThrough(
            Organization::class,
            Project::class,
            'id',
            'id',
            'project_id',
            'organization_id'
        );
    }
}