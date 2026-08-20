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
        return $this->belongsTo(Project::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
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