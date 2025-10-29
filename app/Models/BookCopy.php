<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookCopy extends Model
{
    use HasFactory;

    // protected $primaryKey = 'id'; // ADD THIS LINE

    
    protected $primaryKey = 'book_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'book_id',
        'is_available'
    ];

    

    // Fixed: Added proper foreign key
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    // Fixed: Correct polymorphic relationship
    public function transactions()
    {
         return $this->morphMany(Transaction::class, 'borrowable', 'borrowable_type', 'borrowable_id', 'book_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    // Helpers
    public function isAvailable()
    {
        return $this->is_available;
    }

    public function markAsUnavailable()
    {
        $this->update(['is_available' => false]);
    }

    public function markAsAvailable()
    {
        $this->update(['is_available' => true]);
    }

    // Accessors
    public function getItemTitleAttribute()
    {
        return $this->book ? $this->book->title : 'Unknown Book';
    }

    public function getItemTypeAttribute()
    {
        return 'Book';
    }

    public function getAuthorsAttribute()
{
    if (!$this->book || !$this->book->authors) {
        return collect();
    }
    return $this->book->authors->map(function($author) {
        return trim($author->first_name . ' ' . $author->last_name);
    });
}
public function getAuthorsStringAttribute()
{
    return $this->authors->implode(', ');
}
}