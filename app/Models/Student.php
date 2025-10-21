<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'university_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'university_id', 'academic_program', 'major_department'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'university_id', 'university_id');
    }
}