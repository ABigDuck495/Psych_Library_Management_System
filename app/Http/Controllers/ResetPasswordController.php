<?php
// app/Http/Controllers/Auth/ResetPasswordController.php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\PasswordReset;
use App\Models\LoginAttempt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Generate reset token
        $token = Str::random(60);
        
        // Store token in password_resets table
        PasswordReset::updateOrCreate(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        // Send reset email
        $this->sendResetEmail($user, $token);

        return back()->with('status', 'We have emailed your password reset link!');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
        ]);

        // Find the reset record
        $passwordReset = PasswordReset::where('email', $request->email)->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Invalid reset token.']);
        }

        // Check if token is valid and not expired (24 hours)
        if (!Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['email' => 'Invalid reset token.']);
        }

        if (Carbon::parse($passwordReset->created_at)->addHours(24)->isPast()) {
            $passwordReset->delete();
            return back()->withErrors(['email' => 'Reset token has expired.']);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the used token
        $passwordReset->delete();

        return redirect('/login')->with('status', 'Your password has been reset!');
    }

    protected function sendResetEmail($user, $token)
    {
        //since web based lang to abe ano gagawin natin
        
        \Log::info("Password reset link for {$user->email}: " . url('/password/reset', $token));
        
        // Example email sending (uncomment and configure if you have mail setup)
        /*
        Mail::send('auth.emails.password-reset', [
            'user' => $user,
            'token' => $token,
            'reset_url' => url('/password/reset', $token)
        ], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Password Reset Request');
        });
        */
    }
}