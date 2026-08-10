<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use App\Models\User;


class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'project_id',
    'assigned_to',
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
    

    public function assignedUser()
{
    return $this->belongsTo(
        User::class,
        'assigned_to'
    );
}

}