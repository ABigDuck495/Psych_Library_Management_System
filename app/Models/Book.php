<?php

namespace App\Models;

use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'description', 'year_published', 'category_id'];
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    // public function authors(){
    //     return $this->belongsToMany(Author::class, 'book_authors', 'book_id', 'author_id');
    // }
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_authors');
    }
    public function copies(){
        return $this->hasMany(BookCopy::class);
    }
    public function availableCopies(){
        return $this->hasMany(BookCopy::class)->where('status', 'available');
    }
    public function transactions(){
        return $this->hasManyThrough(Transaction::class, BookCopy::class, 'book_id', 'copy_id', 'id', 'id');
    }
    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->where(function ($q) use ($term) {
            $q->where('title', 'like', $term)
              ->orWhere('isbn', 'like', $term)
              ->orWhereHas('authors', function ($q) use ($term) {
                  $q->where('first_name', 'like', $term)
                    ->orWhere('last_name', 'like', $term);
              });
        });
    }
    public function getAuthorNamesAttribute(): string
    {
        return $this->authors->map(function ($author) {
            return $author->first_name . ' ' . $author->last_name;
        })->join(', ');
    }
    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
    }
    public function scopeByAuthor($query, $authorId)
    {
        if ($authorId) {
            $query->whereHas('authors', function ($q) use ($authorId) {
                $q->where('author_id', $authorId);
            });
        }
    }
    public function scopeAvailable($query)
    {
        return $query->whereHas('copies', function($q) {
            $q->where('is_available', true);
        });
    }
}