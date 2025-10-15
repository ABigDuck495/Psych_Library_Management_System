<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisDept extends Model
{
    protected $table = 'thesis_dept'; // Specify the correct table name
    // Match the migration column names and allow mass assignment
    protected $fillable = ['dept_name', 'dept_code', 'description'];

    // Backwards-compatible accessor so views that expect `$department->name` still work
    public function getNameAttribute()
    {
        return $this->dept_name;
    }
}
