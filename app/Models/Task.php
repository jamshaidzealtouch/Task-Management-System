<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use App\Models\User;
use App\Models\Project;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'created_by',
        'assignee_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function assignee()
    {
        return $this->belongsTo(
            User::class,
            'assignee_id'
        );
    }
    public function projects()
{
    return $this->belongsToMany(
        Project::class,
        'project_user'
    )->withPivot('role')
     ->withTimestamps();
}
}