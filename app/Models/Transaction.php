<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Penalty;
use App\Models\BookCopy;
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
        'transaction_status',
        'copy_type'
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

    public function thesisCopy()
    {
        return $this->belongsTo(ThesisCopy::class, 'copy_id');
    }

    public function copy()
    {
        return $this->morphTo(null, 'copy_type', 'copy_id');
    }
    public function penalty()
    {
        return $this->hasOne(Penalty::class);
    }
    // Query scopes using the transaction_status column
    public function scopeRequested($query)
    {
        return $query->where('transaction_status', 'requested');
    }

    public function scopePending($query)
    {
        return $query->where('transaction_status', 'pending');
    }

    public function scopeBorrowed($query)
    {
        return $query->where('transaction_status', 'borrowed');
    }

    public function scopeReturned($query)
    {
        return $query->where('transaction_status', 'returned');
    }

    public function scopeOverdue($query)
{
    return $query->where(function ($q) {
        $q->where('transaction_status', 'borrowed')
          ->where('due_date', '<', Carbon::now())
          ->whereNull('return_date');
    })
    ->orWhere('transaction_status', 'overdue');
}

    // Other helper methods
    public function calculatePenalty()
    {
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
    public function getOverdueDays(){
        if (!$this->isOverdue()) {
            return 0;
        }

        return Carbon::now()->diffInDays(Carbon::parse($this->due_date));
    }
    public function markAsOverdue(){
        $this->transaction_status = 'overdue';
        $this->save();
    }
    public function calculatePenaltyAmount(){
        $overdueDays = $this->getOverdueDays();
        $penaltyRate = config('library.daily_penalty_rate', 50);
        return $overdueDays * $penaltyRate;
    }
    public function markAsReturned()
    {
        $this->return_date = now();
        $this->transaction_status = 'returned';
        $this->save();
    }

}
