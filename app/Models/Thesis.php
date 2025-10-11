<?php

namespace App\Models;

use App\Models\ThesisDept;
use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Factories\HasFactory;

class Thesis extends Model
{
    use HasFactory;

    protected $fillable = [
        'SKU',
        'dept_id',
        'title',
        'abstract',
        'year_published',
        'advisor',
        'pages'
    ];

    public function department(){
        return $this->belongsTo(ThesisDept::class, 'dept_id');
    }
    public function authors(){
        return $this->belongsToMany(Author::class);
    }
    public function getAuthorNamesAttribute(): string
    {
        return $this->authors->map(function($author){
            return $author->first_name . ' ' . $author->last_name;
        })->join(', ');
    }
    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->where(function ($q) use ($term) {
            $q->where('title', 'like', $term)
              ->orWhere('SKU', 'like', $term)
              ->orWhere('year_published', 'like', $term)
              ->orWhereHas('authors', function ($q) use ($term) {
                  $q->where('first_name', 'like', $term)
                    ->orWhere('last_name', 'like', $term);
              });
        });
    }
    public function scopeByDepartment($query, $deptId)
    {
        return $query->where('dept_id', $deptId);
    }
    

}
