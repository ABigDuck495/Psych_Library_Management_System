<?php

namespace App\Models;

use App\Models\ThesisDept;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Thesis extends Model
{
    protected $fillable = [
        'title',
        'abstract',
        'year_published',
        'department',
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'thesis_authors');
    }
}
