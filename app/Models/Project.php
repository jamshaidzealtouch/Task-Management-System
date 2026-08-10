<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;
use App\Models\Organization;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'organization_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | User Relationship
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Organization Relationship
    |--------------------------------------------------------------------------
    */

    public function organization()
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Tasks Relationship
    |--------------------------------------------------------------------------
    */

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}