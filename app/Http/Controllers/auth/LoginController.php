<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
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
