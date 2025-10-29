<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Penalty;
use App\Models\BookCopy;
use App\Models\ThesisCopy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'borrowable_id',
        'borrowable_type',
        'transaction_status',
        'borrow_date',
        'due_date',
        'return_date',
    ];

    protected $casts = [
        'borrow_date' => 'datetime',
        'due_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    // Fixed: Added proper foreign key
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Fixed: Correct polymorphic relationship
    public function borrowable()
    {
        return $this->morphTo();
    }

    // Fixed: Added proper foreign key
    public function penalty()
    {
        return $this->hasOne(Penalty::class, 'transaction_id');
    }

    // Query Scopes
    public function scopeRequested($query)
    {
        return $query->where('transaction_status', 'requested');
    }

    public function scopeApproved($query)
    {
        return $query->where('transaction_status', 'approved');
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

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePending($query)
    {
        return $query->where('transaction_status', 'requested');
    }

    // Helper Methods
    public function isOverdue(): bool
    {
        return $this->transaction_status === 'borrowed' && 
               is_null($this->return_date) && 
               $this->due_date->isPast();
    }

    public function getOverdueDays(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        return Carbon::now()->diffInDays($this->due_date);
    }

    public function calculatePenaltyAmount(): float
    {
        $overdueDays = $this->getOverdueDays();
        $penaltyRate = config('library.daily_penalty_rate', 20);
        return $overdueDays * $penaltyRate;
    }

    public function markAsOverdue()
    {
        $this->update(['transaction_status' => 'overdue']);
    }

    // Fixed: Added proper polymorphic handling
    public function markAsReturned()
{
    $this->update([
        'return_date' => now(),
        'transaction_status' => 'returned',
    ]);

    if ($this->borrowable) {
        $this->borrowable->markAsAvailable();
    }
}

public function markAsBorrowed()
{
    $this->update(['transaction_status' => 'borrowed']);

    if ($this->borrowable) {
        $this->borrowable->markAsUnavailable();
    }
}

    public function markAsApproved()
    {
        $this->update(['transaction_status' => 'borrowed']);
    }

    // Accessors
    public function getItemTitleAttribute()
    {
        return $this->borrowable ? $this->borrowable->item_title : 'Unknown Item';
    }

    public function getItemTypeAttribute()
    {
        return $this->borrowable ? $this->borrowable->item_type : 'Unknown';
    }

    public function getIsActiveAttribute()
    {
        return in_array($this->transaction_status, ['requested', 'approved', 'borrowed']);
    }

    public function getCanBeRenewedAttribute()
    {
        return $this->transaction_status === 'borrowed' && 
               !$this->isOverdue() &&
               $this->due_date->diffInDays(now()) < 3;
    }


    //bagoooooooooooooooooooooooooooooooooooooooooooooooooooo
//     public function bookCopy()
// {
//     return $this->belongsTo(\App\Models\BookCopy::class, 'copy_id', 'id');
// }

// public function thesisCopy()
// {
//     return $this->belongsTo(\App\Models\ThesisCopy::class, 'copy_id', 'id');
// }

// public function copy()
// {
//     if ($this->borrowable_type === BookCopy::class) {
//         return $this->belongsTo(BookCopy::class, 'copy_id');
//     }
public function copy()
{
    return $this->morphTo('borrowable');
}

//     if ($this->borrowable_type === ThesisCopy::class) {
//         return $this->belongsTo(ThesisCopy::class, 'copy_id');
//     }

//     // Optional: fallback in case it's neither
//     return null;
// }

}