<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookAuthor extends Model
{
    protected $fillable = ['book_id', 'author_id'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
    public function scopeByBook($query, $bookId)
    {
        return $query->where('book_id', $bookId);
    }
    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }
}
