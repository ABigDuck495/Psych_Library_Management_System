<?php
// app/Models/LoginAttempt.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginAttempt extends Model
{
    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'successful',
        'failure_reason',
        'attempted_at'
    ];
    
    public $timestamps = false;
    
    protected $casts = [
        'attempted_at' => 'datetime',
        'successful' => 'boolean'
    ];

    // Relationship to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}