<?php

namespace App\Models;

use App\Models\Author;
use App\Models\ThesisCopy;
use App\Models\ThesisDept;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Thesis extends Model
{
    protected $fillable = [
        'id',
        'title',
        'abstract',
        'year_published',
        'department',
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'thesis_authors');
    }
    public function copies()
    {
        return $this->hasMany(ThesisCopy::class, 'thesis_id');
    }
    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%")
            ->orWhere('SKU', 'like', "%{$search}%")
            ->orWhere('abstract', 'like', "%{$search}%")
            ->orWhere('advisor', 'like', "%{$search}%")
            ->orWhereHas('authors', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
    }

    public function scopeByDepartment($query, $deptId)
    {
        return $query->where('dept_id', $deptId);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year_published', $year);
    }

    // Methods
    public function getAuthorsListAttribute()
    {
        return $this->authors->pluck('full_name')->implode(', ');
    }

    public function getAuthorIdsAttribute()
    {
        return $this->authors->pluck('id')->toArray();
    }

    public function getShortAbstractAttribute()
    {
        return strlen($this->abstract) > 200 
            ? substr($this->abstract, 0, 200) . '...' 
            : $this->abstract;
    }

    // Sync authors with validation
    public function syncAuthors(array $authorIds)
    {
        // Validate that all author IDs exist
        $existingAuthors = Author::whereIn('id', $authorIds)->pluck('id')->toArray();
        
        if (count($existingAuthors) !== count($authorIds)) {
            $invalidIds = array_diff($authorIds, $existingAuthors);
            throw new \Exception('Some author IDs are invalid: ' . implode(', ', $invalidIds));
        }

        // Sync the authors (this handles attaching/detaching automatically)
        return $this->authors()->sync($authorIds);
    }
    public function availableCopies()
    {
        return $this->hasMany(ThesisCopy::class)->where('is_available', true);
    }
}
