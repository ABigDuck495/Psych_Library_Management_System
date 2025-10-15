<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisDept extends Model
{
    protected $table = 'thesis_dept'; // Specify the correct table name
    protected $fillable = ['name'];
}
