<?php

namespace App\Models;

use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'year_published', 'category_id'];
    
    // Fixed: Added proper foreign key
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Fixed: Added proper table and foreign keys
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_authors', 'book_id', 'author_id');
    }

    // Fixed: Added foreign key
    public function copies()
    {
        return $this->hasMany(BookCopy::class, 'book_id');
    }

    // Fixed: Added foreign key and condition
    public function availableCopies()
    {
        return $this->hasMany(BookCopy::class, 'book_id')->where('is_available', true);
    }

    public function transactions()
    {
        return $this->hasManyThrough(
            Transaction::class, 
            BookCopy::class, 
            'book_id', // Foreign key on book_copies table
            'borrowable_id', // Foreign key on transactions table
            'id', // Local key on book table
            'id' // Local key on thesis_copies table
        )->where('borrowable_type', BookCopy::class);
    }

    public function viewDetails()
{
    return Transaction::where('borrowable_type', BookCopy::class)
        ->whereIn('borrowable_id', function ($query) {
            $query->select('id')
                  ->from('book_copies')
                  ->where('book_id', $this->id);
        });
}

    // In Book.php - ensure this method exists and works
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

//if my problem pwede ibalik, gagawan ko nalang bagong function name, pero for now tanggal muna
    // public function hasUserRequested($userId)
    // {
    //     return $this->transactions()
    //                 ->where('user_id', $userId)
    //                 ->whereIn('transaction_status', ['requested', 'approved', 'borrowed'])
    //                 ->exists();
    // }

    public function hasUserRequested($userId)
{
    return $this->viewDetails()
                ->where('user_id', $userId)
                ->whereIn('transaction_status', ['requested', 'approved', 'borrowed'])
                ->exists();
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

    public function hasAvailableCopies()
    {
        return $this->copies()->where('is_available', true)->exists();
    }

    public static function availableBooks(){
        return self::whereHas('copies', function($q) {
            $q->where('is_available', true);
        })->get();
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