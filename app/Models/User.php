<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Penalty;
use App\Models\Transaction;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getfullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super-admin']);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
    public function activeTransactions(){
        return $this->hasMany(Transaction::class)->where('transaction_status', 'borrowed');
    }
    public function overdueTransactions(){
        return $this->hasMany(Transaction::class)->where('transaction_status', 'overdue');
    }
    public function hasPendingRequestForBook($bookId)
    {
        // Check transactions for copies that belong to the given book id
        return $this->transactions()
                    ->whereHas('bookCopy', function ($q) use ($bookId) {
                        $q->where('book_id', $bookId);
                    })
                    // treat both 'requested' and 'pending' as pending states if your app uses either
                    ->whereIn('transaction_status', ['requested', 'pending'])
                    ->exists();
    }


}
