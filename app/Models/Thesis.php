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
    protected $casts = [
        'year_published' => 'integer',
        'pages' => 'integer',
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
    public function getAuthorsListAttribute()
    {
        return $this->authors->pluck('full_name')->implode(', ');
    }

    public function getAuthorIdsAttribute()
    {
        return $this->authors->pluck('id')->toArray();
    }
        // Sync authors with validation
    public function syncAuthors(array $authorIds)
    {
        $existingAuthors = Author::whereIn('id', $authorIds)->pluck('id')->toArray();
        
        if (count($existingAuthors) !== count($authorIds)) {
            throw new \Exception('Some author IDs are invalid');
        }

        return $this->authors()->sync($authorIds);
    }
    

}
