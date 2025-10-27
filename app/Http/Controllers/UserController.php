<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
// use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
// use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class UserController extends Controller
{
    public function __construct()
    {
        // Only admin and super-admin can access all user management functions
        $this->middleware('auth');
        $this->middleware('can:manage-users')->except(['show']);
    }

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
        DB::beginTransaction();
        try {
            $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'university_id' => 'nullable|string|max:50',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // at least one lowercase letter
                'regex:/[A-Z]/',      // at least one uppercase letter
                'regex:/[0-9]/',      // at least one digit
                'regex:/[@$!%*#?&]/', // at least one special character
            ],
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.',
            'role' => 'required|string',
            'user_type' => 'required|string',
            'account_status' => 'required|string',
            'position_title' => 'nullable|string|max:255',
            'academic_program' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'major_department' => 'nullable|string|max:255',
            
        ]);

        $user = User::create([
            'university_id' => $validated['university_id'] ?? null,
            'username' => $validated['username'],
            'email' => $validated['email'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'role' => $validated['role'],
            'account_status' => $validated['account_status'] ?? 'Active',
            'phone_number' => $validated['phone_number'] ?? null,
            'password' => bcrypt($validated['password']),
            'user_type' => $validated['user_type'] ?? null,
        ]);

        if($validated['user_type'] === 'student'){
            // Create associated student record
            Student::create([
                'id' => $user->id,
                'academic_program' => $validated['academic_program'],
                'department' => $validated['major_department']
                // Add other default student fields if necessary
            ]);
        } elseif ($validated['user_type'] === 'employee'){
            // Create associated employee record
            Employee::create([
                'id' => $user->id,
                'position_title' => $validated['position_title'],
                'department' => $validated['department']
                // Add other default employee fields if necessary
            ]);
        }
        DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Failed to create user: ' . $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }
    // Show method (like show.php)
    public function show($id)
    {
        $user = User::findOrFail($id);
        if($user->user_type === 'student'){
            $details = $user->student;
        }else if($user->user_type === 'employee'){
            $details = $user->employee;
        }else{
            $detils = null;
        }
        return view('users.show', compact('user', 'details'));
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
        
        DB::beginTransaction();
        try {
            $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'nullable|string',
            'user_type' => 'nullable|string',
            'account_status' => 'nullable|string',
            'username' => 'required|string|max:255|unique:users,username,',
        ]);
        if($request['user_type'] === 'student'){
            // Create associated student record
            Student::create([
                'id' => $user->id,
                'academic_program' => $request['academic_program'],
                'department' => $request['department']
                // Add other default student fields if necessary
            ]);
        } elseif ($request['user_type'] === 'employee'){
            // Create associated employee record
            Employee::create([
                'id' => $user->id,
                'position' => $request['position'],
                'department' => $request['department']
                // Add other default employee fields if necessary
            ]);
        }
        DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Failed to update user: ' . $e->getMessage());
        }
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