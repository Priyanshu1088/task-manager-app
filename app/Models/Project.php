<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
     protected $fillable = [
        'name',
        'status',
    ];

    // Project belongs to an employee
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Project belongs to a manager
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    } 

    public function tasks()
    {
        return $this->hasMany(Tasks::class);
    }
}
