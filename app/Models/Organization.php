<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'owner_id',
    ];

    /**
     * Organization owner (Admin).
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Users belonging to this organization.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Projects belonging to this organization.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}