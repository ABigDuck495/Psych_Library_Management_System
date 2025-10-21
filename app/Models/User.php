<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Student;
use App\Models\Employee;
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
        'username', 'email', 'password', 'first_name', 'last_name', 'role',
        'phone_number', 'account_status', 'user_type'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'last_login_date' => 'datetime',
    ];
    public function student(){
        return $this->hasOne(Student::class, 'university_id', 'id');
    }
    public function employee(){
        return $this->hasOne(Employee::class, 'university_id', 'id');
    }
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
    public function getfullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super-admin']);
    }
    public function isStudent()
    {
        return $this->user_type === 'student';
    }
    public function isEmployee()
    {
        return $this->user_type === 'employee';
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
    public function scopeStudents($query)
    {
        return $query->where('user_type', 'student');
    }

    public function scopeEmployees($query)
    {
        return $query->where('user_type', 'employee');
    }

    public function scopeActive($query)
    {
        return $query->where('account_status', 'Active');
    }

}
