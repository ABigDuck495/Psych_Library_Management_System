<?php
// app/Http/Traits/LogsLoginAttempts.php
namespace App\Http\Traits;

use App\Models\LoginAttempt;
use Carbon\Carbon;

trait LogsLoginAttempts
{
    protected function logLoginAttempt($email, $successful, $failureReason = null)
    {
        LoginAttempt::create([
            'email' => $email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'successful' => $successful,
            'failure_reason' => $failureReason,
            'attempted_at' => Carbon::now()
        ]);
    }

    protected function isAccountLocked($email)
    {
        $recentAttempts = LoginAttempt::where('email', $email)
            ->where('attempted_at', '>=', Carbon::now()->subMinutes(30))
            ->where('successful', false)
            ->count();

        return $recentAttempts >= 5; // Lock after 5 failed attempts in 30 minutes
    }

    protected function getRemainingAttempts($email)
    {
        $recentAttempts = LoginAttempt::where('email', $email)
            ->where('attempted_at', '>=', Carbon::now()->subMinutes(30))
            ->where('successful', false)
            ->count();

        return max(0, 5 - $recentAttempts);
    }
}