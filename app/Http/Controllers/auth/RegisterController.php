<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate registration data
        $validated = $request->validate([
            'university_id' => 'required|string|max:255|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'string|max:50',
            'phone_number' => 'string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create user
        $user = User::create([
            'university_id' => $validated['university_id'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => 'admin', // Default role
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'], // Assign role from input
        ]);

        // Log the user in
        Auth::login($user);

        return redirect('/')->with('success', 'Registration successful!');
    }
}