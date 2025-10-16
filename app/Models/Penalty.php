<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount',
        'status',
        'paid_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function incrementPayment($amount)
    {
        $days = now()->diffInDays($this->issued_date);
        if ($days > 0) {
            $this->amount += ($days * 50);
            $this->save();
        }
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function scopePaid($query){
        return $query->where('status', 'paid');
    }
    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->paid_date = now();
        $this->save();
    }
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    public function waive(){
        $this->update([
            'status' => 'waived',
            'paid_date' => now()
        ]);
    }
}
