<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookCopy extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'book_id',
        'is_available',
        'status',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'copy_id');
    }
    public function currrentTransaction()
    {
        return $this->hasOne(Transaction::class, 'copy_id')->whereNull('return_date')->latest();
    }
    public function isAvailable(): bool
    {
        return $this->is_available;
    }
    public function getBorrowHistory(){
        return $this->transactions()->with('user')->orderBy('borrow_date', 'desc')->get();
    }
}
