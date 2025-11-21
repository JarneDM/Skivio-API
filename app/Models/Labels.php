<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Labels extends Model
{
    protected $fillable = [
        'name',
        'color',
        'project_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
