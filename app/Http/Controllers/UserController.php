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
        $users = User::all();
        return view('users.index', compact('users'));
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
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $user->update($request->all());

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

}