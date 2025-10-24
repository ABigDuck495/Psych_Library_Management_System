<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'academic_program', 'department'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}