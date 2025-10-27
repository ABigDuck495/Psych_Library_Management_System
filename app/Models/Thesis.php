<?php

namespace App\Models;

use App\Models\Author;
use App\Models\ThesisCopy;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Thesis extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'abstract',
        'year_published',
        'department',
    ];

    // Fixed: Added proper foreign key and table names
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'thesis_authors', 'thesis_id', 'author_id');
    }

    // Fixed: Correct polymorphic relationship
    public function transactions()
    {
        return $this->hasManyThrough(
            Transaction::class, 
            ThesisCopy::class, 
            'thesis_id', // Foreign key on thesis_copies table
            'borrowable_id', // Foreign key on transactions table
            'id', // Local key on theses table
            'id' // Local key on thesis_copies table
        )->where('borrowable_type', ThesisCopy::class);
    }

    // Fixed: Added foreign key
    public function copies()
    {
        return $this->hasMany(ThesisCopy::class, 'thesis_id');
    }

    // Fixed scope methods
    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%")
            ->orWhere('abstract', 'like', "%{$search}%")
            ->orWhereHas('authors', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
    }

    // Fixed: Added missing department field reference
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year_published', $year);
    }

    // Accessors
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

    // Fixed: Added proper validation and error handling
    public function syncAuthors(array $authorIds)
    {
        $existingAuthors = Author::whereIn('id', $authorIds)->pluck('id')->toArray();
        
        if (count($existingAuthors) !== count($authorIds)) {
            $invalidIds = array_diff($authorIds, $existingAuthors);
            throw new \Exception('Some author IDs are invalid: ' . implode(', ', $invalidIds));
        }

        return $this->authors()->sync($authorIds);
    }
    public static function availableTheses(){
        return self::whereHas('copies', function($q) {
            $q->where('is_available', true);
        })->get();
    }

    // Fixed: Added proper scope
    public function availableCopies()
    {
        return $this->hasMany(ThesisCopy::class, 'thesis_id')->where('is_available', true);
    }

    // Fixed: Added proper parameter type and validation
    public function markCopyUnavailable($copyId)
    {
        $copy = ThesisCopy::find($copyId);
        if ($copy && $copy->thesis_id === $this->id) {
            $copy->markAsUnavailable();
        }
    }

    public function getNextAvailableCopy()
    {
        return $this->copies()->available()->first();
    }

    public function canBeRequested()
    {
        return $this->copies()->available()->exists();
    }

    public function availableCopiesCount()
    {
        return $this->copies()->available()->count();
    }

    // Fixed: Added proper status check
    public function hasUserRequested($userId)
    {
        return $this->transactions()
                    ->where('user_id', $userId)
                    ->whereIn('transaction_status', ['requested', 'approved', 'borrowed'])
                    ->exists();
    }
}