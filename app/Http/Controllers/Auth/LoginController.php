<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\LogsLoginAttempts;

class LoginController extends Controller
{

    use LogsLoginAttempts;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function attemptLogin(Request $request)
    {
        // Check if account is locked
        if ($this->isAccountLocked($request->email)) {
            $this->logLoginAttempt($request->email, false, 'Account temporarily locked due to too many failed attempts');
            return false;
        }

        $credentials = $this->credentials($request);
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $this->logLoginAttempt($request->email, true);
            return true;
        }

        // Log failed attempt
        $remaining = $this->getRemainingAttempts($request->email);
        $failureReason = $remaining > 0 ? 
            "Invalid credentials. {$remaining} attempts remaining." : 
            "Account locked. Too many failed attempts.";

        $this->logLoginAttempt($request->email, false, $failureReason);

        return false;
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $remaining = $this->getRemainingAttempts($request->email);
        
        if ($this->isAccountLocked($request->email)) {
            $errors = [$this->username() => 'Your account has been temporarily locked due to too many failed attempts. Please try again in 30 minutes.'];
        } else {
            $errors = [$this->username() => trans('auth.failed') . " ({$remaining} attempts remaining)"];
        }

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
    public function authenticate(Request $request)
{
    $credentials = $request->validate([
        'email' => [
            'required',
            'email',
            'regex:/^[\w\.-]+@(?:gmail\.com|google\.com|yahoo\.com|clsu\.edu\.ph)$/i',
        ],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        $user = Auth::user();
        $user->last_login_date = now();
        $user->save();

        // Check role and redirect accordingly
        switch ($user->role) {
            case 'admin':
                return redirect()->route('adminInterface.index');
            case 'librarian':
                return redirect()->route('librarianInterface.index');
            case 'user':
                return redirect()->route('userInterface.index');
            default:
                Auth::logout();
                abort(403, 'Unauthorized role.');
        }
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

}
