<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    protected $fillable = [
    'name',
    'deadline',
    'project_id',
    'employee_id',
    'status',
    'comment',
    'completed_at',
    'description',
    'created_by'
];
protected $casts = [
        'completed_at' => 'datetime',
    ];

    protected static function boot()
{
    parent::boot();

    static::updating(function ($task) {

        if ($task->isDirty('status')) {

            // ✅ When completed
            if ($task->status === 'Completed') {
                $task->completed_at = now();
            }

            // ❌ When changed back
            else {
                $task->completed_at = null;
            }

        }

    });
} 

public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
