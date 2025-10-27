<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Employee;
use App\Models\Transaction;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'university_id',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'role',
        'phone_number',
        'user_type',
        'account_status',
        'registration_date',
        'last_login_date',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'last_login_date' => 'datetime',
    ];

    // Fixed: Added proper foreign keys
    public function student()
    {
        return $this->hasOne(Student::class, 'id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'id');
    }

    // Fixed: Added proper foreign key
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
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
    public function isLibrarian()
    {
        return $this->role === 'librarian';
    }

    // Fixed: Added proper foreign key and status condition
    public function activeTransactions()
    {
        return $this->hasMany(Transaction::class, 'user_id')->where('transaction_status', 'borrowed');
    }

    public function overdueTransactions()
    {
        return $this->hasMany(Transaction::class, 'user_id')->where('transaction_status', 'overdue');
    }

    // Fixed: Added proper polymorphic relationships
    public function hasPendingRequestForBook($bookId)
    {
        return $this->transactions()
                    ->whereIn('transaction_status', ['requested', 'approved'])
                    ->whereHasMorph('borrowable', [BookCopy::class], function ($query) use ($bookId) {
                        $query->whereHas('book', function ($q) use ($bookId) {
                            $q->where('id', $bookId);
                        });
                    })
                    ->exists();
    }

    public function hasPendingRequestForThesis($thesisId)
    {
        return $this->transactions()
                    ->whereIn('transaction_status', ['requested', 'approved'])
                    ->whereHasMorph('borrowable', [ThesisCopy::class], function ($query) use ($thesisId) {
                        $query->whereHas('thesis', function ($q) use ($thesisId) {
                            $q->where('id', $thesisId);
                        });
                    })
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