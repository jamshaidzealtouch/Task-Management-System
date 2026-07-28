<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Project extends Model
{
    protected $fillable = [
        'user_id',

        'title',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}