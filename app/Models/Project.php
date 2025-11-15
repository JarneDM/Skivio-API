<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'team_id',
    ];

    public function statuses()
    {
        return $this->hasMany(Statuses::class, 'project_id');
    }
    public function tasks()
    {
        return $this->hasMany(Tasks::class, 'project_id');
    }
}