<?php

namespace App\Models;

use App\Models\Book;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'description'
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
    public function getBookCountAttribute(): int
    {
        return $this->books()->count();
    }
    public function scopeWithBooks($query)
    {
        return $query->whereHas('books');
    }
    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->where(function ($q) use ($term) {
            $q->where('category_name', 'like', $term)
              ->orWhere('description', 'like', $term);
        });
    }

}
