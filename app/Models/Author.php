<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Thesis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\database\Eloquent\Factories\HasFactory;

class Author extends Model
{
    use HasFactory;
    protected $fillable = ['first_name', 'last_name'];
    protected $table = 'authors';

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }
    public function thesis()
    {
        return $this->belongsToMany(Thesis::class);
    }
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function getBookCountAttribute(): int
    {
        return $this->books()->count();
    }
    public function getThesisCountAttribute(): int
    {
        return $this->thesis()->count();
    }

    public function scopeWithBooks($query)
    {
        return $query->whereHas('books');
    }
    public function scopeWithThesis($query)
    {
        return $query->whereHas('thesis');
    }
    public function orderByName(){
        return $this->orderBy('last_name')->orderBy('first_name');
    }
}
