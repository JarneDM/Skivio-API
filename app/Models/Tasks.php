<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status_id',
        'project_id',
        'assigned_to',
        'position',
        'due_date',
    ];

    public function status()
    {
        return $this->belongsTo(Statuses::class, 'status_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}