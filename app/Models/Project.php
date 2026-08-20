<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ProjectStatus;
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
            Organization::class
        );
    }
    public function invitations()
{
    return $this->hasMany(Invitation::class);
}

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role');
    }
    public function tasks()
    {
        return $this->hasMany(
            Task::class
        );
    }
}