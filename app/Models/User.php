<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Enums\UserRole;
use App\Models\Project;
use App\Models\Organization;
use App\Models\Task;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'organization_id',
        'primary_project_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function projects()
    {
        return $this->belongsToMany(
            Project::class,
            'project_user'
        )
            ->withPivot('role')
            ->withTimestamps();
    }
    public function primaryProject()
{
    return $this->belongsTo(
        Project::class,
        'primary_project_id'
    );
}

    public function ownedOrganizations()
    {
        return $this->hasMany(
            Organization::class,
            'owner_id'
        );
    }

    public function organizations()
    {
        return $this->belongsToMany(
            Organization::class,
            'organization_user'
        )->withTimestamps();
    }

    public function assignedTasks()
    {
        return $this->hasMany(
            Task::class,
            'assignee_id'
        );
    }

    public function createdTasks()
    {
        return $this->hasMany(
            Task::class,
            'created_by'
        );
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }
}