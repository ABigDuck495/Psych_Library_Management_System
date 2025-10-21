<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     // Only admin and super-admin can access all user management functions
    //     $this->middleware('auth');
    //     $this->middleware('can:manage-users')->except(['show']);
    // }

    // show all users (like index.php)
    public function index()
    {
        $query = User::query();

        // Search
        if (request('search')) {
            $s = request('search');
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('username', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if (request('role')) {
            $query->where('role', request('role'));
        }

        if (request('user_type')) {
            $query->where('user_type', request('user_type'));
        }

        if (request('account_status')) {
            $query->where('account_status', request('account_status'));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('users.index', compact('users'));
    }
    
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'role' => 'required|string',
            'user_type' => 'nullable|string',
            'account_status' => 'nullable|string'
        ]);

        // create with a random password (admin can reset)
        $user = User::create(array_merge($validated, [
            'password' => bcrypt(str()->random(12)),
            'account_status' => $validated['account_status'] ?? 'Active'
        ]));

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }
    // Show method (like show.php)
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    // Edit method (like edit.php)
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Update method to handle form submission
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'nullable|string',
            'user_type' => 'nullable|string',
            'account_status' => 'nullable|string'
        ]);

        $user->update($request->only(['first_name','last_name','email','role','user_type','account_status','username']));

        return redirect()->route('users.show', $user->id)
                         ->with('success', 'User updated successfully');
    }
    // Delete method
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')
                         ->with('success', 'User deleted successfully');
    }

    // Activate user account
    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->account_status = 'Active';
        $user->save();
        return redirect()->back()->with('success', 'User activated.');
    }

    // Deactivate user account
    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->account_status = 'Inactive';
        $user->save();
        return redirect()->back()->with('success', 'User deactivated.');
    }

}