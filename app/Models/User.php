<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Project;
use App\Models\Organization;
use App\Models\Task;
use App\Enums\UserRole;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'organization_id',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function projects()
    {
        return $this->belongsToMany(Project::class)
                    ->withPivot('role');
    }
    public function organizations()
    {
        return $this->hasMany(
            Organization::class,
            'owner_id'
        );
    }
    public function organization()
    {
        return $this->belongsTo(
            Organization::class
        );
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