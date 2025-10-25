<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'amount',
        'reason',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function activePenalties()
    {
        return Transaction::where('transaction_status', 'overdue')->get()->paginate(10);
    }
}
