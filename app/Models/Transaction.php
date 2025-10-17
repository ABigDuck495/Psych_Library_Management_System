<?php

namespace App\Models;

use App\Models\Penalty;
use App\Models\BookCopy;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'copy_id',
        'borrow_date',
        'return_date',
        'due_date',
        'status',
        'transaction_status'
    ];
    protected $casts = [
        'borrow_date' => 'datetime',
        'return_date' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class, 'copy_id');
    }
    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->whereNull('return_date');
    }
    public function calculatePenalty(){
        if ($this->transaction_status !== 'overdue' || $this->return_date) {
            return 0;
        }

        $daysOverdue = now()->diffInDays($this->due_date);
        $penaltyRate = config('library.daily_penalty_rate', 50);
        
        return $daysOverdue * $penaltyRate;
    }
    public function isOverdue(): bool
    {
        return $this->transaction_status === 'overdue' && is_null($this->return_date) && $this->due_date->isPast();
    }
    public function markAsReturned()
    {
        $this->return_date = now();
        $this->transaction_status = 'returned';
        $this->save();

    }
}
