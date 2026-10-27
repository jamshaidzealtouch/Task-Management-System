<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'project_id',
        'created_by',
        'assignee_id',
        'assignment_status',
        'attachment_path',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(
            Project::class
        );
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
}