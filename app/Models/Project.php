<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ProjectStatus;
use App\Models\Organization;
use App\Models\User;
use App\Models\Task;
use App\Models\Invitation;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'organization_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => ProjectStatus::class,
    ];

    public function organization()
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id'
        );
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'project_user'
        )->withPivot('role');
    }

    public function tasks()
    {
        return $this->hasMany(
            Task::class,
            'project_id'
        );
    }

    public function invitations()
    {
        return $this->hasMany(
            Invitation::class,
            'project_id'
        );
    }
}