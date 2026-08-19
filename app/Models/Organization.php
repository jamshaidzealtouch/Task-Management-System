<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invitation;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'owner_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    // user relation 
    public function users()
    {
        return $this->hasMany(User::class);
    }
    //project relation 
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    //invitation relation
    public function invitations()
{
    return $this->hasMany(Invitation::class);
}
}