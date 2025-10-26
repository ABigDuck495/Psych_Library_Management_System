<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Thesis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name'];
    protected $table = 'authors';

    // Relationships
    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_authors');
    }

    public function theses()
    {
        return $this->belongsToMany(Thesis::class, 'thesis_authors');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getBookCountAttribute(): int
    {
        return $this->books()->count();
    }

    public function getThesisCountAttribute(): int
    {
        return $this->theses()->count();
    }

    // Scopes
    public function scopeWithBooks($query)
    {
        return $query->whereHas('books');
    }

    public function scopeWithTheses($query)
    {
        return $query->whereHas('theses');
    }
}
